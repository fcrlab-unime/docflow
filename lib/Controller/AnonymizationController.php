<?php

namespace OCA\Docflow\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\Docflow\Db\MethodsDataMapper;

use OCA\Docflow\Db\Anonymization;
use OCA\Docflow\Db\Methods;
use OCP\AppFramework\Http;
use OCP\IUserSession;
use OCP\Files\IRootFolder;
use Exception;
use OCA\DAV\Connector\Sabre\TagList;
use OCA\Docflow\Db\MethodsTag;

class AnonymizationController extends Controller
{

    private MethodsDataMapper $mapperMethodsData;

    public function __construct(string $AppName, IRequest $request, MethodsDataMapper $mapperMethodsData, ?string $UserId = null)
    {
        parent::__construct($AppName, $request);
        $this->mapperMethodsData = $mapperMethodsData;
    }

    public function getDocflow($list, $elem)
    {

        foreach ($list as $l) {

            if ($l->getSensitiveDataId() == (int)$elem["sensitive_data_id"]) {

                return $l;
            }
        }

        return null;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getData(): DataResponse
    {

        $listElements = $this->mapperMethodsData->findAllWithoutPath();
        $listDocflow = [];

        foreach ($listElements as $elem) {

            $currentDocflow = $this->getDocflow($listDocflow, $elem);

            if (empty($listDocflow) || $currentDocflow == null) {

                $docflow = new Anonymization();

                $docflow->setSensitiveDataId((int)$elem["sensitive_data_id"]);
                $docflow->setData($elem["data"]);
                $docflow->setExclusivityS($elem["exclusivity_s"]);

                $method = new MethodsTag();

                $method->setMethodsId((int)$elem["methods_id"]);
                $method->setDesc($elem["desc"]);
                $method->setExclusivityM($elem["exclusivity_m"]);
                $method->setDefault((bool)(int)$elem["default"]);
                $tagList = explode(";", $elem["tag"]);
                $method->setTagList($tagList);

                $docflow->addMethods($method);

                array_push($listDocflow, $docflow);
            } else {

                $methodsList = $currentDocflow->getMethods();
                $foundMethod = false;

                foreach ($methodsList as $m) {

                    if ($m->getMethodsId() == $elem["desc"]) {

                        $foundMethod = true;
                    }
                }

                if ($foundMethod == false) {

                    $method = new MethodsTag();

                    $method->setMethodsId((int)$elem["methods_id"]);
                    $method->setDesc($elem["desc"]);
                    $method->setExclusivityM($elem["exclusivity_m"]);
                    $method->setDefault((bool)(int)$elem["default"]);
                    $tagList = explode(";", $elem["tag"]);
                    $method->setTagList($tagList);

                    $currentDocflow->addMethods($method);
                }
            }
        }

        return new DataResponse($listDocflow);
    }


    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function anonymize($elem): DataResponse
    {
        
        if($elem["file"] == ""){
            return new DataResponse("", Http::STATUS_BAD_REQUEST);
        }
        

        if($elem["sensitiveData"]){

            if(!file_exists("./custom_apps/docflow/pandoc/inputs")){
                mkdir("./custom_apps/docflow/pandoc/inputs", 0777, true);
            }
            if(!file_exists("./custom_apps/docflow/pandoc/outputs")){
                mkdir("./custom_apps/docflow/pandoc/outputs", 0777, true);
            }

            $readFileStart = microtime(true);
              
            $user = \OC::$server->get(IUserSession::class)->getUser();
            $userId = $user->getUID();
            $storage = \OC::$server->get(IRootFolder::class);
            $userFolder= $storage->getUserFolder($userId);
            $inputFileNcPath = \OC\Files\Filesystem::getPath($elem["file"]);
            $userInputFile = $userFolder->get($inputFileNcPath); 
            $userInputFileName = explode(".", $userInputFile->getName())[0];
            $userInputFileFolder = $userInputFile->getParent();
            $inputFileContent = $userInputFile->getContent();
    
            $fileName = "document-" . $userId. "-" . strval(microtime(true));
            $inputFilePath = "./custom_apps/docflow/pandoc/inputs/" . $fileName . ".md";
            file_put_contents($inputFilePath, $inputFileContent);
            //return(new DataResponse($fileName . ".md"));
            $output = null;
            $ret_val = null;

            $readFileEnd = microtime(true);

            foreach ($elem["sensitiveData"] as $sensitiveData) {

                foreach ($sensitiveData["methods"] as $method) {
    
                    $result = $this->mapperMethodsData->findByIdDataAndMethods($sensitiveData["sensitiveDataId"], $method);
    
                    exec("pandoc -f markdown -t markdown --wrap=preserve --lua-filter " . $result->getPath() . " " . $inputFilePath . " -o " . $inputFilePath, $output, $ret_val);
                    
                }
    
            }
    
            exec("pandoc -f markdown -t markdown --wrap=preserve --lua-filter ./custom_apps/docflow/pandoc/filters/clear-all.lua " . $inputFilePath . " -o " . $inputFilePath, $output, $ret_val);
            $outputFilePath = "./custom_apps/docflow/pandoc/outputs/" . $fileName . ".pdf";
            exec("pandoc " . $inputFilePath . " -o " . $outputFilePath . " --from markdown --template justsmart.tex --listings", $output, $ret_val);
            //exec ("rm " . $inputFilePath, $output, $ret_val);
            unlink($inputFilePath);

            $conversionsEnd = microtime(true);
            
            $outputFileContent = file_get_contents($outputFilePath);
    
            //return new DataResponse($permissions);
            $userOutputFileName = $userInputFileName . '.pdf';
            $alreadyExistsOutput = $userInputFileFolder->nodeExists($userOutputFileName);
            $count = 0;
            while($alreadyExistsOutput){
                $userOutputFileName = $userInputFileName . "-". strval($count). '.pdf';
                $count++;
                $alreadyExistsOutput = $userInputFileFolder->nodeExists($userOutputFileName);
            }
            try {
                try {
                    $outputFile = $userInputFileFolder->get($userOutputFileName);
                    $outputFile->putContent($outputFileContent);
                } catch(\OCP\Files\NotFoundException $e) {
                    $userInputFileFolder->newFile($userOutputFileName);
    
                    $outputFile = $userInputFileFolder->get($userOutputFileName);
                
                    $outputFile->putContent($outputFileContent);
    
                }
            } catch(\OCP\Files\NotPermittedException $e) {
                throw new Exception('Cant write to file');
            }
    
            /* $outputFile = base64_encode(file_get_contents($outputFilePath));  */
            //exec ("rm " . $outputFilePath, $output, $ret_val);
            unlink($outputFilePath);
            $returnPathParts = explode ("/", $outputFile->getParent()->getInternalPath());
            array_shift($returnPathParts);
            $returnPath = "/";
            foreach($returnPathParts as $part){
                $returnPath = $returnPath . $part;
            }

            $endTime = microtime(true);

            $returnObject = [
                "dir" => $returnPath,
                "id" => $outputFile->getId(),
                "processingTime" => $endTime - $readFileStart,
                "readFileTime" => $readFileEnd - $readFileStart,
                "conversionsTime" => $conversionsEnd - $readFileEnd,
                "writeFileTime" => $endTime - $conversionsEnd
            ];
            return new DataResponse($returnObject);
        }
        else {
            return new DataResponse("", Http::STATUS_BAD_REQUEST);
        }


    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getMdText($fileId): DataResponse
    {
        $user = \OC::$server->get(IUserSession::class)->getUser();
        $userId = $user->getUID();
        $storage = \OC::$server->get(IRootFolder::class);
        $userFolder= $storage->getUserFolder($userId);
        $inputFileNcPath = \OC\Files\Filesystem::getPath($fileId);
        $userInputFile = $userFolder->get($inputFileNcPath); 
        $userInputFileMimeType = $userInputFile->getMimeType();
        if($userInputFileMimeType != "text/markdown"){
            return new DataResponse("Selected file is not a .md file", Http::STATUS_BAD_REQUEST);
        }
        $userInputFileName = $userInputFile->getName();
        $inputFileContent = $userInputFile->getContent();
        $fileData = [
            "name" => $userInputFileName,
            "content" => $inputFileContent
        ];
        return new DataResponse($fileData);
    }
}

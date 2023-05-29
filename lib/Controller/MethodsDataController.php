<?php
 namespace OCA\Docflow\Controller;

 use Exception;
 use OCP\IRequest;
 use OCP\AppFramework\Http\DataResponse;
 use OCP\AppFramework\Controller;

 use OCA\Docflow\Db\MethodsDataMapper;
 use OCP\AppFramework\Http;

 class MethodsDataController extends Controller {

    private MethodsDataMapper $mapper;

    public function __construct(string $AppName, IRequest $request, MethodsDataMapper $mapper, ?string $UserId = null){
        parent::__construct($AppName, $request);
        $this->mapper = $mapper;
    }


    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index(): DataResponse {
       
        return new DataResponse($this->mapper->findAll());
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function indexJoin(): DataResponse {
       
        return new DataResponse($this->mapper->findAllJoin());
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function show(int $id): DataResponse {
        try {
            return new DataResponse($this->mapper->find($id));
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
    */
    public function showByIdDataAndMethods(int $sensitiveDataId,int $methodsId): DataResponse {
        try {
            return new DataResponse($this->mapper->findByIdDataAndMethods($sensitiveDataId, $methodsId));
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function insert(int $sensitiveDataId, int $methodsId, string $path, int $default, string $tag) {

        try {
            return new DataResponse($this->mapper->create($sensitiveDataId, $methodsId, $path, $default, $tag));
        } catch(Exception $e) {
            return new DataResponse($e);
        }
    }

    /**
      * @NoAdminRequired
      * @NoCSRFRequired
      *
      */
      public function update(int $methodsDataId, int $sensitiveDataId, int $methodsId, string $path, int $default, string $tag) {
        try {
            $methodsData = $this->mapper->find($methodsDataId);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        $methodsData->setMethodsDataId($methodsDataId);
        $methodsData->setSensitiveDataId($sensitiveDataId);
        $methodsData->setMethodsId($methodsId);
        $methodsData->setPath($path);
        $methodsData->setDefault($default);
        $methodsData->setTag($tag);

        return new DataResponse($this->mapper->update($methodsData));

    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function delete(int $id) {

        try {
            $methodsData = $this->mapper->find($id);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        
        return new DataResponse($this->mapper->delete($methodsData));

    }

}
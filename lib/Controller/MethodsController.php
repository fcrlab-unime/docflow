<?php
 namespace OCA\Docflow\Controller;

use Exception;
use OCP\IRequest;
 use OCP\AppFramework\Http\DataResponse;
 use OCP\AppFramework\Controller;

 use OCA\Docflow\Db\MethodsMapper;
use OCP\AppFramework\Http;

 class MethodsController extends Controller {

    private MethodsMapper $mapper;

    public function __construct(string $AppName, IRequest $request, MethodsMapper $mapper, ?string $UserId = null){
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
    public function showByDesc(string $desc): DataResponse {
        try {
            return new DataResponse($this->mapper->findByDesc($desc));
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function insert(string $desc, int $exclusivity) {

        try {
            return new DataResponse($this->mapper->create($desc, $exclusivity));
        } catch(Exception $e) {
            return new DataResponse($e);
        }
    }

    /**
      * @NoAdminRequired
      * @NoCSRFRequired
      *
      */
      public function update(int $methodsId, string $desc, int $exclusivity) {
        try {
            $method = $this->mapper->find($methodsId);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        $method->setMethodsId($methodsId);
        $method->setDesc($desc);
        $method->setExclusivityM($exclusivity);

        return new DataResponse($this->mapper->update($method));

    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function delete(int $id) {

        try {
            $method = $this->mapper->find($id);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        
        return new DataResponse($this->mapper->delete($method));

    }

}
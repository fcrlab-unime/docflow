<?php
 namespace OCA\Docflow\Controller;

use Exception;
use OCP\IRequest;
 use OCP\AppFramework\Http\DataResponse;
 use OCP\AppFramework\Controller;

 use OCA\Docflow\Db\SensitiveDataMapper;
use OCP\AppFramework\Http;

 class SensitiveDataController extends Controller {

    private SensitiveDataMapper $mapper;

    public function __construct(string $AppName, IRequest $request, SensitiveDataMapper $mapper, ?string $UserId = null){
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
    public function showByData(string $data): DataResponse {
        try {
            return new DataResponse($this->mapper->findByData($data));
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function insert(string $data, int $exclusivity) {

        try {
            return new DataResponse($this->mapper->create($data, $exclusivity));
        } catch(Exception $e) {
            return new DataResponse($e);
        }
    }

    /**
      * @NoAdminRequired
      * @NoCSRFRequired
      *
      */
      public function update(int $sensitiveDataId, string $data, int $exclusivity) {
        try {
            $sensData = $this->mapper->find($sensitiveDataId);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        $sensData->setSensitiveDataId($sensitiveDataId);
        $sensData->setData($data);
        $sensData->setExclusivityS($exclusivity);

        return new DataResponse($this->mapper->update($sensData));

    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function delete(int $id) {

        try {
            $sensData = $this->mapper->find($id);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        
        return new DataResponse($this->mapper->delete($sensData));

    }

}
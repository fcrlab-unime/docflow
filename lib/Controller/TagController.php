<?php
namespace OCA\Docflow\Controller;

use Exception;
use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\Docflow\Db\TagMapper;
use OCP\AppFramework\Http;

 class TagController extends Controller {

    private TagMapper $mapper;

    public function __construct(string $AppName, IRequest $request, TagMapper $mapper, ?string $UserId = null){
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
    public function showByLabel(string $label): DataResponse {
        try {
            return new DataResponse($this->mapper->findByLabel($label));
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function insert(string $label, string $tagString) {

        try {
            return new DataResponse($this->mapper->create($label, $tagString));
        } catch(Exception $e) {
            return new DataResponse($e);
        }
    }

    /**
      * @NoAdminRequired
      * @NoCSRFRequired
      *
      */
      public function update(int $tagId, string $label, string $tagString) {
        try {
            $tag = $this->mapper->find($tagId);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        $tag->setTagId($tagId);
        $tag->setLabel($label);
        $tag->setTagString($tagString);

        return new DataResponse($this->mapper->update($tag));

    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function delete(int $id) {

        try {
            $tag = $this->mapper->find($id);
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
        
        return new DataResponse($this->mapper->delete($tag));

    }

}
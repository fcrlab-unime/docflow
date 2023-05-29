<?php
namespace OCA\Docflow\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

use OCA\Docflow\Db\MethodsData;

/**
 * @extends QBMapper<MethodsData>
 */
class MethodsDataMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'docflow_methods_data', MethodsData::class);
    }

    public function findAll() {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
           ->from($this->getTableName())
           ->where(1);

        return $this->findEntities($qb);
    }

    public function findAllJoin() {

        $qb = $this->db->getQueryBuilder();

        $qb->select('md.methods_data_id', 'md.sensitive_data_id', 's.data', 'md.methods_id', 'm.desc', 'md.path', 'md.tag', 's.exclusivity_s', 'm.exclusivity_m')
            ->from($this->getTableName(), 'md')
            ->join('md', 'docflow_sensitive_data', 's', 'md.sensitive_data_id=s.sensitive_data_id')
            ->join('md', 'docflow_methods', 'm', 'md.methods_id=m.methods_id')
            ->orderBy('md.methods_data_id', 'ASC')
            ->where(1);    

        $ans = [];
        $cursor = $qb->executeQuery();

        while($row = $cursor->fetch()){
            array_push($ans, $row);
        }

        $cursor->closeCursor();

        return $ans;
    }

    public function findAllWithoutPath() {

        $qb = $this->db->getQueryBuilder();

        $qb->select('md.methods_data_id', 'md.sensitive_data_id', 's.data', 'md.methods_id', 'm.desc', 'md.default', 'md.tag','s.exclusivity_s', 'm.exclusivity_m')
            ->from($this->getTableName(), 'md')
            ->join('md', 'docflow_sensitive_data', 's', 'md.sensitive_data_id=s.sensitive_data_id')
            ->join('md', 'docflow_methods', 'm', 'md.methods_id=m.methods_id')
            ->orderBy('md.methods_data_id', 'ASC')
            ->where(1);    

        $ans = [];
        $cursor = $qb->executeQuery();

        while($row = $cursor->fetch()){
            array_push($ans, $row);
        }

        $cursor->closeCursor();

        return $ans;
    }

    public function find(int $id) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
             ->from($this->getTableName())
             ->where($qb->expr()->eq('methods_data_id', $qb->createNamedParameter($id)));

        return $this->findEntity($qb);
    }

    public function findByIdDataAndMethods(int $sensitiveDataId,int $methodsId) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
             ->from($this->getTableName())
             ->where($qb->expr()->eq('sensitive_data_id', $qb->createNamedParameter($sensitiveDataId)))
             ->andWhere($qb->expr()->eq('methods_id', $qb->createNamedParameter($methodsId)));

        return $this->findEntity($qb);
    }

    public function create(int $sensitiveDataId, int $methodsId, string $path, int $default, string $tag){

        $qb = $this->db->getQueryBuilder();

        $qb ->insert($this->getTableName())
            ->values([
                'sensitive_data_id' => '?',
                'methods_id' => '?',
                'path' => '?',
                'default' =>  '?',
                'tag' => '?',
            ])
            ->setParameter(0, $sensitiveDataId)
            ->setParameter(1, $methodsId)
            ->setParameter(2, $path)
            ->setParameter(3, $default)
            ->setParameter(4, $tag);

        $qb->executeStatement();

    }

    public function update($methodsData): MethodsData {

		$qb = $this->db->getQueryBuilder();

        $qb ->update($this->getTableName(), "md")
            ->set('md.sensitive_data_id', $qb->createNamedParameter($methodsData->getSensitiveDataId()))
            ->set('md.methods_id', $qb->createNamedParameter($methodsData->getMethodsId()))
            ->set('md.path', $qb->createNamedParameter($methodsData->getPath()))
            ->set ('md.default', $qb->createNamedParameter($methodsData->getDefault()))
            ->set('md.tag', $qb->createNamedParameter($methodsData->getTag()))
            ->where($qb->expr()->eq('methods_data_id', $qb->createNamedParameter($methodsData->getMethodsDataId())));

        $qb->executeStatement();

        return $methodsData;
	}

    public function delete($methodsData): MethodsData {

		$qb = $this->db->getQueryBuilder();

        $qb ->delete($this->getTableName())
            ->where($qb->expr()->eq('methods_data_id', $qb->createNamedParameter($methodsData->getMethodsDataId())));

        $qb->executeStatement();

        return $methodsData;
	}

}
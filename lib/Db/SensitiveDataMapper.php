<?php
namespace OCA\Docflow\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

use OCA\Docflow\Db\SensitiveData;

/**
 * @extends QBMapper<SensitiveData>
 */
class SensitiveDataMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'docflow_sensitive_data', SensitiveData::class);
    }


    public function findAll() {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
           ->from($this->getTableName())
           ->where(1);

        return $this->findEntities($qb);
    }

    public function find(int $id) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
             ->from($this->getTableName())
             ->where($qb->expr()->eq('sensitive_data_id', $qb->createNamedParameter($id)));

        return $this->findEntity($qb);
    }

    public function findByData(string $data) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
             ->from($this->getTableName())
             ->where($qb->expr()->eq('data', $qb->createNamedParameter($data)));

        return $this->findEntity($qb);
    }

    public function create(string $data, int $exclusivity){

        $qb = $this->db->getQueryBuilder();

        $qb ->insert($this->getTableName())
            ->values([
                'data' => '?',
                'exclusivity_s' => '?'
            ])
            ->setParameter(0, $data)
            ->setParameter(1, $exclusivity);

        $qb->executeStatement();

    }

    public function update($sensitiveData): SensitiveData {

		$qb = $this->db->getQueryBuilder();

        $qb ->update($this->getTableName(), "s")
            ->set('s.data', $qb->createNamedParameter($sensitiveData->getData()))
            ->set('s.exclusivity_s', $qb->createNamedParameter($sensitiveData->getExclusivityS()))
            ->where($qb->expr()->eq('sensitive_data_id', $qb->createNamedParameter($sensitiveData->getSensitiveDataId())));

        $qb->executeStatement();

        return $sensitiveData;
	}

    public function delete($sensitiveData): SensitiveData {

		$qb = $this->db->getQueryBuilder();

        $qb ->delete($this->getTableName())
            ->where($qb->expr()->eq('sensitive_data_id', $qb->createNamedParameter($sensitiveData->getSensitiveDataId())));

        $qb->executeStatement();

        return $sensitiveData;
	}

}
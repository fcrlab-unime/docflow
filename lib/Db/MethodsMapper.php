<?php
namespace OCA\Docflow\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

use OCA\Docflow\Db\Methods;

/**
 * @extends QBMapper<Methods>
 */
class MethodsMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'docflow_methods', Methods::class);
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
             ->where($qb->expr()->eq('methods_id', $qb->createNamedParameter($id)));

        return $this->findEntity($qb);
    }

    public function findByDesc(string $desc) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
             ->from($this->getTableName())
             ->where($qb->expr()->eq('desc', $qb->createNamedParameter($desc)));

        return $this->findEntity($qb);
    }

    public function create(string $desc, int $exclusivity){

        $qb = $this->db->getQueryBuilder();

        $qb ->insert($this->getTableName())
            ->values([
                'desc' => '?',
                'exclusivity_m' => '?'
            ])
            ->setParameter(0, $desc)
            ->setParameter(1, $exclusivity);

        $qb->executeStatement();

    }

    public function update($method): Methods {

		$qb = $this->db->getQueryBuilder();

        $qb ->update($this->getTableName(), "m")
            ->set('m.desc', $qb->createNamedParameter($method->getDesc()))
            ->set('m.exclusivity_m', $qb->createNamedParameter($method->getExclusivityM()))
            ->where($qb->expr()->eq('methods_id', $qb->createNamedParameter($method->getMethodsId())));

        $qb->executeStatement();

        return $method;
	}

    public function delete($method): Methods {

		$qb = $this->db->getQueryBuilder();

        $qb ->delete($this->getTableName())
            ->where($qb->expr()->eq('methods_id', $qb->createNamedParameter($method->getMethodsId())));

        $qb->executeStatement();

        return $method;
	}

}
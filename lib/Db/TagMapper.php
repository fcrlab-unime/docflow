<?php
namespace OCA\Docflow\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

use OCA\Docflow\Db\Tag;

/**
 * @extends QBMapper<Tag>
 */
class TagMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'docflow_tag', Tag::class);
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
             ->where($qb->expr()->eq('tag_id', $qb->createNamedParameter($id)));

        return $this->findEntity($qb);
    }

    public function findByLabel(string $label) {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
             ->from($this->getTableName())
             ->where($qb->expr()->eq('label', $qb->createNamedParameter($label)));

        return $this->findEntity($qb);
    }

    public function create(string $label, string $tagString){

        $qb = $this->db->getQueryBuilder();

        $qb ->insert($this->getTableName())
            ->values([
                'label' => '?',
                'tag_string' => '?'
            ])
            ->setParameter(0, $label)
            ->setParameter(1, $tagString);

        $qb->executeStatement();

    }

    public function update($tag): Tag {

		$qb = $this->db->getQueryBuilder();

        $qb ->update($this->getTableName(), "t")
            ->set('t.tag_string', $qb->createNamedParameter($tag->getTagString()))
            ->set('t.label', $qb->createNamedParameter($tag->getLabel()))
            ->where($qb->expr()->eq('tag_id', $qb->createNamedParameter($tag->getTagId())));

        $qb->executeStatement();

        return $tag;
	}

    public function delete($tag): Tag {

		$qb = $this->db->getQueryBuilder();

        $qb ->delete($this->getTableName())
            ->where($qb->expr()->eq('tag_id', $qb->createNamedParameter($tag->getTagId())));

        $qb->executeStatement();

        return $tag;
	}

}
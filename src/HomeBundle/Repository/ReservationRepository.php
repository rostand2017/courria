<?php

namespace HomeBundle\Repository;

/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends \Doctrine\ORM\EntityRepository
{
    public function countByDateAndName($concertId, $nbPage, $page, $begin=null, $end=null, $search=null){
        $page = (int) $page;
        $nbPage = (int) $nbPage;
        $sql="SELECT COUNT(*) FROM client c INNER JOIN reservation r ON  c.id = r.cli_id WHERE r.con_id = :concert_id";

        if($search && !$begin)
            $sql .= " AND c.nom LIKE :search OR c.prenom LIKE :search ";
        else if($search && $begin)
            $sql .= " AND c.createdat BETWEEN :begin AND :end AND (c.nom LIKE :search OR c.prenom LIKE :search ) ";
        else if(!$search && $begin)
            $sql .= " AND c.createdat BETWEEN :begin AND :end ";

        $sql .= " ORDER BY c.createdat DESC";

        $state = $this->_em->getConnection()->prepare($sql);
        if($begin) {
            $begin = date_format( new \DateTime($begin), "y-m-d");
            $end = date_format( new \DateTime($end), "y-m-d");
            $state->bindValue(":begin", $begin, \PDO::PARAM_STR);
            $state->bindValue(":end", $end, \PDO::PARAM_STR);
        }
        if($search) {
            $search = '%'.$search.'%';
            $state->bindValue(":search", $search, \PDO::PARAM_STR);
        }
        $state->bindValue(":concert_id", $concertId,\PDO::PARAM_INT);
        $state->execute();
        return $state->fetch(\PDO::FETCH_COLUMN);
    }
    public function getByDateAndName($concertId, $limit, $offset, $begin=null, $end=null, $search=null){
        $offset = (int) $offset;
        $limit = (int) ($limit - 1) *$offset;
        //$sql="SELECT c.id, c.nom, c.prenom, c.sexe, c.photo, c.createdat, c.age, c.email, r.id FROM client c INNER JOIN reservation r ON c.id = r.cli_id";
        $sql="SELECT * FROM client c INNER JOIN reservation r ON  c.id = r.cli_id WHERE r.con_id = :concert_id";

        if($search && !$begin)
            $sql .= " AND c.nom LIKE :search OR c.prenom LIKE :search ";
        else if($search && $begin)
            $sql .= " AND c.createdat BETWEEN :begin AND :end AND (c.nom LIKE :search OR c.prenom LIKE :search) ";
        else if(!$search && $begin)
            $sql .= " AND c.createdat BETWEEN :begin AND :end ";

        $sql .= " ORDER BY c.createdat DESC ";
        $sql .= " LIMIT :limit,:offset";

        $state = $this->_em->getConnection()->prepare($sql);
        if($begin){
            $begin = date_format( new \DateTime($begin), "y-m-d");
            $end = date_format( new \DateTime($end), "y-m-d");
            $state->bindValue(":begin", $begin,\PDO::PARAM_STR);
            $state->bindValue(":end", $end,\PDO::PARAM_STR);
        }
        if($search) {
            $search = '%'.$search.'%';
            $state->bindValue(":search", $search, \PDO::PARAM_STR);
        }

        $state->bindValue(":limit", $limit,\PDO::PARAM_INT);
        $state->bindValue(":offset", $offset,\PDO::PARAM_INT);
        $state->bindValue(":concert_id", $concertId,\PDO::PARAM_INT);
        $state->execute();
        return $state->fetchAll();
    }
}

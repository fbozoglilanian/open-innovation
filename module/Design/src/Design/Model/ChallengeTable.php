<?php
namespace Design\Model;

use Zend\Db\TableGateway\TableGateway;

class ChallengeTable
{
	/**
	 * 
	 * @var TableGateway
	 */
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
//     public function getLastChallenges($limit = 5)
//     {
//     	$resultSet = $this->tableGateway->select(function (Select $select) {
//     		$select->order('date_added ASC')->limit((int) $limit);
//     		return $select;
//     	});
//     		$resultSet->
//     	return $resultSet;
//     }
    
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getChallenge($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('challenge_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveChallenge(Challenge $challenge)
    {
        $data = array(
            'challenge' => $challenge->challenge,
        	'motivation' => $challenge->motivation,
        	'user_id' => $challenge->userId,
        );

        $id = (int)$album->id;
        if ($id == 0) {
        	$challenge->dateAdded = $data['date_added'] = date("Y-m-d H:i:s", time());
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getChallenge($id)) {
            	$challenge->dateEdited = $data['date_edited'] = date("Y-m-d H:i:s", time());
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Challenge id does not exist');
            }
        }
        return $id;
    }

//     public function deleteAlbum($id)
//     {
//         $this->tableGateway->delete(array('id' => $id));
//     }
}
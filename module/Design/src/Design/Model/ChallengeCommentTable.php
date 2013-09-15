<?php
namespace Design\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class ChallengeCommentTable
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
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getComments($challengeId) {
	    $resultSet = $this->tableGateway->select(array('challenge_id' => $challengeId));
        return $resultSet;
	}

	public function addComment(ChallengeComment $comment)
	{
		$data = array(
				'challenge_id' => $comment->challengeId,
				'comment' => $comment->comment,
				'user_id' => $comment->userId,
		);

		$id = (int)$comment->id;
		if ($id == 0) {
			$data['date_added'] = date("Y-m-d H:i:s", time());
			$this->tableGateway->insert($data);
			$id = $this->tableGateway->getLastInsertValue();
			$comment->id = $id;
		}
		$comment->dateAdded = $data['date_added'];
		
		return $id;
	}
}
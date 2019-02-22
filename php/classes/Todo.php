<?php
namespace uss-hopper\assessment;

require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

use Fullstack\Assessment\ValidateDate;
use Fullstack\Assessment\ValidateUuid;
use Ramsey\Uuid\Uuid;

/**
 * The to do class will contain the identifying info.
 */
class Todo implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;

	/**
	 * Id for todo, this is the primary key
	 * @var string | Uuid $todoId
	 */
	private $todoId;

	/**
	 *value of author
	 *@var string $todoAuthor
	 */
	private $todoAuthor;

	/**
	 *date of todo
	 *@var /Datetime $todoDate
	 */
	private $todoDate;

	/**
	 * task for todo
	 * @var string $todoTask;
	 */
}
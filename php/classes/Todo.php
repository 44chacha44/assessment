<?php
namespace uss-hopper\assessment;

require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

use Fullstack\Assessment\ValidateDate;
use Fullstack\Assessment\ValidateUuid;
use http\Exception\InvalidArgumentException;
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
	private $todoTask;

	/**
	 * Constructor for Todo
	 *
	 * @param string|Uuid $newTodoId id for this todo, null if a new todo
	 * @param string $newTodoAuthor is authors name
	 * @param \DateTime|string $newTodoDate date of todo
	 * @param string $newTodoTask info of task
	 * @throws \InvalidArgumentException if data types are not InvalidArgumentException
	 * @throws \RangeException if data values are out of bounds (strings too long, negative integers, etc)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 *
	 **/
	public function __construct($newTodoId, string $newTodoAuthor, $newTodoDate, string $newTodoTask) {
				try {
						$this->setTodoId($newTodoId);
						$this->setTodoAuthor($newTodoAuthor);
						$this->setTodoDate($newTodoDate);
						$this->setTodoTask($newTodoTask);
				} catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
						//determine what exception type was thrown
						$exceptionType = get_class($exception);
						throw(new $exceptionType($exception->getMessage(), 0, $exception));
				}
	}
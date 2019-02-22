<?php
namespace uss-hopper\assessment;

require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

use Fullstack\Assessment\ValidateDate;
use Fullstack\Assessment\ValidateUuid;
use http\Exception\BadQueryStringException;
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

	/**
	 * Accessor method for todoId
	 * @return Uuid value of todoId or null if new
	 */
	public function getTodoId(): Uuid {
				return ($this->todoId);
	}

	/**
	 * Mutator method for todoId
	 *
	 *@param Uuid| string $newTodoId value of new todoid
	 *@throws \RangeExceptionif $newTodoId is not positive
	 *@throws \TypeError if the id is not correct type
	 */
	public function setTodoId( $newTodoId): void {
				try {
						$uuid = self::validateUuid($newTodoId);
				}	catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
							$exceptionType = get_class($exception);
							throw(new $exceptionType($exception->getMessage(), 0, $exception));
				}
				//convert and store the todoId
		$this->todoId = $uuid;
	}

	/**
	 * Accessor method for todoAuthor
	 * @return string value of author
	 */
	public function getTodoAuthor(): string {
				return ($this->todoAuthor);
	}

	/**
	 * Mutator method for todoAuthor
	 *
	 * @param string $newTodoAuthor new value of author
	 * @throws \InvalidArgumentException if $newTodoAuthor is not a string or insecure
	 * @throws \RangeException if $newTodoAuthor is > 32 characters
	 * @throws \TypeError if $newTodoAuthor is not a BadQueryStringException
	 *
	 **/
	public function setTodoAuthor(string $newTodoAuthor) : void {
				//verify the author is secure
				$newTodoAuthor = trim($newTodoAuthor);
				$newTodoAuthor = filter_var($newTodoAuthor, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
				if(empty($newTodoAuthor) === true) {
						throw(new \InvalidArgumentException("Author is empty or insecure"));
				}
				// verify the author will fit in the database
				if(strlen($newTodoAuthor) > 32) {
						throw(new \RangeException("Author is too long"));
				}
				//store the author
				$this->todoAuthor = $newTodoAuthor;
	}

	/**
	 * Accessor method for todoDate
	 * @return \DateTime value of todoDate
	 */
	public function getTodoDate() : \DateTime {
				return($this->todoDate);
	}

	/**
	 * Mutator method for todoDate
	 *
	 * @param \DateTime|string $newTodoDate date of todo
	 * @throws \InvalidArgumentException if $newTodoDate is not a valid object or BadQueryStringException
	 * @throws \RangeException if $newTodoDate is a date that does not exist
	 * @throws \TypeError if $todoDate is not a /DateTime
	 *
	 **/
	public function setTodoDate($newTodoDate) : void {
				//store the end date using the ValidateDate trait
				try {
						$newTodoDate = self::validateDateTime($newTodoDate);
				} catch (\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
					$exceptionType = get_class($exception);
					throw(new $exceptionType($exception->getMessage(), 0, $exception));
				}
				//convert and store the date
				$this->todoDate = $newTodoDate;
	}
}
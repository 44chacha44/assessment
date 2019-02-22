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
	public function setTodoId($newTodoId): void {
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
	 * @throws \TypeError if $newTodoAuthor is not a string
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
	/**
	 * Accessor method for todoTask
	 * @return string value of task
	 */
	public function getTodoTask(): string {
		return ($this->todoTask);
	}

	/**
	 * Mutator method for todoTask
	 *
	 * @param string $newTodoTask new value of task
	 * @throws \InvalidArgumentException if $newTodoTask is not a string or insecure
	 * @throws \RangeException if $newTodoTask is > 255 characters
	 * @throws \TypeError if $newTodoTask is not a string
	 *
	 **/
	public function setTodoTask(string $newTodoTask) : void {
		//verify the string is secure
		$newTodoTask = trim($newTodoTask);
		$newTodoTask = filter_var($newTodoTask, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newTodoTask) === true) {
			throw(new \InvalidArgumentException("Task is empty or insecure"));
		}
		// verify the task will fit in the database
		if(strlen($newTodoTask) > 255) {
			throw(new \RangeException("Task is too long"));
		}
		//store the task
		$this->todoTask = $newTodoTask;
	}

	/**
	 * Inserts this todo into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 **/
	public function insert(\PDO $pdo) : void {
				//create query template
				$query = "INSERT INTO todo(todoId, todoAuthor, todoDate, todoTask) VALUES(:todoId, :todoAuthor, :todoDate, :todoTask)";
				$statement = $pdo->prepare($query);
				// bind the variables to the place holders in the template
				$formattedDate = $this->todoDate->format("Y-m-d H:i:s.u");

				$parameters = [
					"todoId" => $this->todoId->getBytes(),
					"todoAuthor" => $this->todoAuthor,
					"todoDate" => $formattedDate,
					"todoTask" => $this->todoTask,
					];
			$statement->execute($parameters);
	}

	/**
	 * Gets the todo by todoId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $todoId id to search by
	 * @return todo|null todo found or null if not found
	 * @throws \PDOException when my sql related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getTodoByTodoId(\PDO $pdo, $todoId) : Todo {
				//sanitize the id before searching
				try {
						$todoId = self::validateUuid($todoId);
				}	catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
							throw(new \PDOException($exception->getMessage(), 0, $exception));
				}

				//create query template
				$query = "INSERT INTO todo(todoId, todoAuthor, todoDate, todoTask) VALUES(:todoId, :todoAuthor, :todoDate, :todoTask)";
				$statement = $pdo->prepare($query);

				// bind the todo id to the place holder in the template
				$parameters = ["todoId" => $todoId->getBytes()];
				$statement->execute($parameters);

				//Grab the event from mysql
				try {
							$todo = null;
							$statement->setFetchMode(\PDO::FETCH_ASSOC);
							$row = $statement->fetch();
							if($row !== false) {
									$todo = new todo($row["todoId"], $row["todoAuthor"], $row["todoDate"], $row["todoTask"]);
							}
				}	catch(\Exception $exception) {
							// if the row couldn't be converted, rethrow it
							throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
				return($todo);
	}

	/**
	 * Gets todo by Author
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $todoAuthor todo's content to search for
	 * @return \SplFixedArray an array of todo's found
	 * @throws \PDOException when mysql related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 *
	 **/
	public static function getTodoByTodoAuthor(\PDO $pdo, string $todoAuthor) : \SplFixedArray {
					//sanitize the content before searching
					$todoAuthor = trim($todoAuthor);
					$todoAuthor = filter_var($todoAuthor, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
					if(empty($todoAuthor) === true) {
							throw(new \PDOException("Author is invalid"));
					}

					//escape any mysql wild cards
					$todoAuthor = str_replace("_", "\\_", str_replace("%", "\\%", $todoAuthor));

					//create query template
					$query = "INSERT INTO todo(todoId, todoAuthor, todoDate, todoTask) VALUES(:todoId, :todoAuthor, :todoDate, :todoTask)";
					$statement = $pdo->prepare($query);

					//bind the todoAuthor content to the place holder in the template
					$todos = new \SplFixedArray($statement->rowCount());
					$statement->setFetchMode(\PDO::FETCH_ASSOC);
					while(($row = $statement-fetch()) !== false) {
							try {
									$todo = new Todo($row["todoId"], $row["todoAuthor"], $row["todoDate"], $row["todoTask"]);
									$todos[$todos->key()] = $todo;
									$todos->next();
							}	catch(\Exception $exception) {
									//if the row couldnt be converted, rethrow it
								throw(new \PDOException(($exception->getMessage(), 0, $exception));
							}
					}
					return($todos);
	}
	/**
	 * Formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 */
	public function jsonSerialize() {
				$fields = get_object_vars($this);
				$fields["todoId"] = $this->todoId->toString();
				return ($fields);
	}
}
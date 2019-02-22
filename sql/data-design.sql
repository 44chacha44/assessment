ALTER	DATABASE ###### CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXITS todo;

CREATE TABLE todo (
	todoId BINARY(16) NOT NULL,
	todoAuthor VARCHAR(32) NOT NULL,
	todoDate DATETIME(6) NOT NULL,
	todoTask VARCHAR(255) NOT NULL,
	PRIMARY KEY (todoId)
);
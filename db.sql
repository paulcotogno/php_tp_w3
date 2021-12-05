CREATE TABLE IF NOT EXISTS users
(
    id        int(11)     NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstName varchar(50) NOT NULL,
    lastName  varchar(50) NOT NULL,
    email     varchar(50) NOT NULL,
    isAdmin   boolean     NOT NULL,
    password  varchar(255)
);

CREATE TABLE IF NOT EXISTS posts
(
    id          int(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title       varchar(50)  NOT NULL,
    content     varchar(255) NOT NULL,
    date        DATE         NOT NULL,
    isPublished boolean      NOT NULL,
    userId      int          NOT NULL,
    CONSTRAINT FK_PostUser FOREIGN KEY (userId) REFERENCES users (id) ON DELETE CASCADE 
);

CREATE TABLE IF NOT EXISTS comments
(
    id      int(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content varchar(255) NOT NULL,
    date    DATE         NOT NULL,
    userId  int          NOT NULL,
    postId  int          NOT NULL,
    CONSTRAINT FK_CommentUser FOREIGN KEY (userId) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT FK_CommentPost FOREIGN KEY (postId) REFERENCES posts (id) ON DELETE CASCADE 
);
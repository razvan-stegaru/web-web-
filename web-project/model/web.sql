--deschide sql developer si ruleaza scriptul in acel IDE
--trb sa instalezi OCI8 si instantclient ca sa poti conecta ORACLE prin Xampp la proiect
DROP TABLE users CASCADE CONSTRAINTS;
DROP TABLE pwdReset CASCADE CONSTRAINTS;
DROP TABLE posts CASCADE CONSTRAINTS;

DROP SEQUENCE users_seq;
CREATE SEQUENCE users_seq START WITH 1 INCREMENT BY 1;

DROP SEQUENCE posts_seq;
CREATE SEQUENCE posts_seq START WITH 1 INCREMENT BY 1;

CREATE TABLE users (
    usersID NUMBER(11) PRIMARY KEY,
    usersfirstName VARCHAR2(128) NOT NULL,
    userslastName VARCHAR2(128) NOT NULL,
    usersEmail VARCHAR2(128) NOT NULL,
    usersPwd VARCHAR2(128) NOT NULL
);

CREATE OR REPLACE TRIGGER users_trigger
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    :new.usersID := users_seq.NEXTVAL;
END;
/

CREATE TABLE posts (
    usersID NUMBER(11) NOT NULL,
    postID NUMBER(11) NOT NULL,
    postName VARCHAR2(128) NOT NULL,
    postSubject VARCHAR2(128) NOT NULL,
    CONSTRAINT posts_pk PRIMARY KEY (postID)
);

CREATE OR REPLACE TRIGGER posts_trigger
BEFORE INSERT ON posts
FOR EACH ROW
BEGIN
    :new.postID := posts_seq.NEXTVAL;
END;
/

CREATE TABLE pwdReset (
    pwdResetId NUMBER PRIMARY KEY,
    pwdResetEmail VARCHAR2(255) NOT NULL,
    pwdResetSelector VARCHAR2(255) NOT NULL,
    pwdResetToken VARCHAR2(255) NOT NULL,
    pwdResetExpires TIMESTAMP NOT NULL
);

--select * from users;

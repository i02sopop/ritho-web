DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS user_group;

CREATE TABLE users(
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR2(20) NOT NULL UNIQUE,
       pass VARCHAR2(30) NOT NULL,
       uname VARCHAR2(30),
       usurname VARCHAR2(50),
       uaddress VARCHAR2(50),
       ucp INT,
       ucity VARCHAR2(30),
       ucountry VARCHAR2(30)
);

CREATE TABLE groups(
       id INT AUTO_INCREMENT PRIMARY KEY,
       gname VARCHAR2(30) NOT NULL UNIQUE
);

CREATE TABLE user_group(
       uid INT REFERENCES users.id,
       gid INT REFERENCES groups.id,
       PRIMARY KEY(uid,gid)
);

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS user_group;

CREATE TABLE users(
       id INT PRIMARY KEY,
       username VARCHAR(20) NOT NULL UNIQUE,
       pass VARCHAR(30) NOT NULL,
       u_name VARCHAR(30),
       u_surname VARCHAR(50),
       u_address VARCHAR(50),
       u_cp INT,
       u_city VARCHAR(30),
       u_country VARCHAR(30)
);

CREATE TABLE groups(
       id INT PRIMARY KEY,
       gname VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE user_group(
       uid INT REFERENCES users(id),
       gid INT REFERENCES groups(id),
       PRIMARY KEY(uid,gid)
);

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS user_group;
DROP TABLE IF EXISTS paths;
DROP TABLE IF EXISTS config;

--
-- Table to store the site users.
--

CREATE TABLE users (
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

--
-- Table to store the site groups.
--

CREATE TABLE groups (
       id INT PRIMARY KEY,
       gname VARCHAR(30) NOT NULL UNIQUE
);

--
-- Table to store the relation between users and groups.
--

CREATE TABLE user_group (
       uid INT REFERENCES users(id),
       gid INT REFERENCES groups(id),
       PRIMARY KEY(uid,gid)
);

-- 
-- Table to store the configs of the site.
-- 

CREATE TABLE config (
	c_key VARCHAR(30) PRIMARY KEY,
	c_value VARCHAR(100)
);

--
-- Table to store the path and the controller that responds to it.
--

CREATE TABLE paths (
	c_path VARCHAR(250) PRIMARY KEY,
	controller VARCHAR(50),
	param VARCHAR(30)
);

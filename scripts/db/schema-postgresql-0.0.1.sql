CREATE ROLE writers;
CREATE ROLE readers;

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

GRANT SELECT,INSERT,DELETE,UPDATE ON users TO writers;
GRANT SELECT ON users TO readers;

--
-- Table to store the site groups.
--

CREATE TABLE groups (
       id INT PRIMARY KEY,
       gname VARCHAR(30) NOT NULL UNIQUE
);

GRANT SELECT,INSERT,DELETE,UPDATE ON groups TO writers;
GRANT SELECT ON groups TO readers;

--
-- Table to store the relation between users and groups.
--

CREATE TABLE user_group (
       uid INT REFERENCES users(id),
       gid INT REFERENCES groups(id),
       PRIMARY KEY(uid,gid)
);

GRANT SELECT,INSERT,DELETE,UPDATE ON user_group TO writers;
GRANT SELECT ON user_group TO readers;

-- 
-- Table to store the configs of the site.
-- 

CREATE TABLE config (
	c_key VARCHAR(30) PRIMARY KEY,
	c_value VARCHAR(100)
);

GRANT SELECT,INSERT,DELETE,UPDATE ON config TO writers;
GRANT SELECT ON config TO readers;

--
-- Table to store the path and the controller that responds to it.
--

CREATE TABLE paths (
	c_path VARCHAR(250) PRIMARY KEY,
	controller VARCHAR(50),
	param VARCHAR(30)
);

GRANT SELECT,INSERT,DELETE,UPDATE ON paths TO writers;
GRANT SELECT ON paths TO readers;

--
-- Create database users.
--

CREATE ROLE ritho NOSUPERUSER INHERIT NOCREATEROLE NOCREATEDB LOGIN PASSWORD 'ritho';
GRANT ALL PRIVILEGES ON DATABASE ritho_web TO ritho;
GRANT writers TO ritho;
GRANT readers TO ritho;

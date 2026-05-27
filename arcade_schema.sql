
-- Drop tables if they exist
DROP TABLE IF EXISTS user_favorites;
DROP TABLE IF EXISTS games;
DROP TABLE IF EXISTS genres;
DROP TABLE IF EXISTS platforms;
DROP TABLE IF EXISTS developers;

-- Create tables
CREATE TABLE genres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE platforms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE developers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE games (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  year YEAR NOT NULL,
  genre_id INT,
  platform_id INT,
  developer_id INT,
  embed_url TEXT,
  image_url TEXT,
  FOREIGN KEY (genre_id) REFERENCES genres(id),
  FOREIGN KEY (platform_id) REFERENCES platforms(id),
  FOREIGN KEY (developer_id) REFERENCES developers(id)
);

CREATE TABLE user_favorites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user VARCHAR(50),
  game_id INT,
  FOREIGN KEY (game_id) REFERENCES games(id)
);

-- Insert data
INSERT INTO genres (id, name) VALUES
(1, 'Maze'), (2, 'Shooter'), (3, 'Platformer'), (4, 'Fighting'), (5, 'Puzzle');

INSERT INTO platforms (id, name) VALUES
(1, 'Arcade');

INSERT INTO developers (id, name) VALUES
(1, 'Namco'),
(2, 'Nintendo'),
(3, 'Taito'),
(4, 'Capcom'),
(5, 'Atari Inc.'),
(6, 'Konami'),
(7, 'Gottlieb'),
(8, 'Nichibutsu'),
(9, 'Tekhan'),
(10, 'SunA'),
(11, 'Jaleco'),
(12, 'Data East'),
(13, 'Seibu Kaihatsu'),
(14, 'Technōs Japan'),
(15, 'Toaplan'),
(16, 'Visco'),
(17, 'IGS'),
(18, 'SNK'),
(19, 'Rock-Ola'),
(20, 'SEGA'),
(21, 'Psikyo'),
(22, 'Video System'),
(23, 'Kaneko'),
(24, 'Gaelco'),
(25, 'Semicom'),
(26, 'Falcon');


INSERT INTO games (id, title, year, genre_id, platform_id, developer_id, embed_url, image_url) VALUES
(1, 'Pac-Man', 1980, 1, 1, 1, 'https://tinyurl.com/2yuy5j34', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Pac-Man%20(bootleg%2C%20Video%20Game%20SA).png'),
(2, 'Galaga', 1981, 2, 1, 1, 'https://tinyurl.com/27h2nr7c', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Galaga%20(Midway%20set%201%20with%20fast%20shoot%20hack).png'),
(3, 'Moon Cresta', 1980, 2, 1, 8, 'https://tinyurl.com/22vqgvba', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Moon%20Cresta%20(bootleg%20set%201).png'),
(4, 'Street Fighter II', 1991, 4, 1, 4, 'https://tinyurl.com/23hu62kh', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Street%20Fighter%20II%20-%20Champion%20Edition%20(Alpha%20Magic-F%20bootleg%20set%201%2C%20920313%20etc).png'),
(5, 'Ponpoko', 1982, 3, 1, 3, 'https://tinyurl.com/28twzaty', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Ponpoko.png'),
(6, 'Galaxian', 1979, 2, 1, 1, 'https://tinyurl.com/2xm69aks', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Galaxian%20(bootleg%2C%20set%201).png'),
(7, 'Warp Warp', 1981, 1, 1, 1, 'https://tinyurl.com/29jbbqjx', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Warp%20Warp%20(Rock-Ola%20set%201).png'),
(8, 'Ms. Pac-Man', 1981, 1, 1, 1, 'https://tinyurl.com/22x4rc5d', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Ms.%20Pac-Man%20(\'Made%20in%20Greece\'%20bootleg%2C%20set%201).png'),
(9, 'Dig Dug', 1982, 1, 1, 1, 'https://tinyurl.com/2dbmgs4y', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Dig%20Dug%20(Atari%2C%20rev%202).png'),
(10, 'Bubble Bobble', 1986, 3, 1, 3, 'https://tinyurl.com/2773nufa', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Bubble%20Bobble%20(boolteg%20with%2068705%2C%20set%201).png'),
(11, 'Frogger', 1981, 1, 1, 6, 'https://tinyurl.com/26v9wpj5', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Frogger.png'),
(12, 'Donkey Kong', 1981, 3, 1, 2, 'https://tinyurl.com/22jfqm83', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Donkey%20Kong%20(2600%20graphics%2C%20hack).png'),
(13, 'Donkey Kong Jr.', 1982, 3, 1, 2, 'https://tinyurl.com/23fhyh46', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Donkey%20King%20Jr.%20(bootleg%20of%20Donkey%20Kong%20Jr.).png'),
(14, 'Rompers', 1989, 1, 1, 1, 'https://tinyurl.com/2b2tunwx', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Rompers%20(Japan%2C%20new%20version%20(Rev%20B)).png'),
(15, 'Donkey Kong 3', 1983, 3, 1, 2, 'https://tinyurl.com/2axzmnly', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Donkey%20Kong%203%20(bootleg%20on%20Donkey%20Kong%20Jr.%20hardware).png'),
(16, 'Gyruss', 1982, 2, 1, 6, 'https://tinyurl.com/2yfp4kvv', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Gyruss%20(bootleg).png'),
(17, 'King & Balloon', 1980, 2, 1, 1, 'https://tinyurl.com/235ztpd2', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/King%20%26%20Balloon%20(Japan).png'),
(18, 'Kicker', 1985, 3, 1, 6, 'https://tinyurl.com/25rvcw3h', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Kicker.png'),
(19, 'Splatterhouse', 1988, 3, 1, 1, 'https://tinyurl.com/2xtlgaxj', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Splatter%20House%20(Japan%2C%20SH1).png'),
(20, 'Mappy', 1983, 3, 1, 1, 'https://tinyurl.com/23m6n9q6', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Mappy%20(Japan).png'),
(21, 'Bomb Jack', 1982, 3, 1, 9, 'https://tinyurl.com/2346osbj', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Bomb%20Jack%20(set%201).png'),
(22, 'Pooyan', 1982, 2, 1, 6, 'https://tinyurl.com/2ar7o3rt', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Pooyan.png'),
(23, 'Metro Cross', 1985, 3, 1, 1, 'https://tinyurl.com/2d2h4jrd', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Metro-Cross%20(set%201).png'),
(24, 'Goindol', 1987, 5, 1, 10, 'https://tinyurl.com/238nh2se', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Goindol%20(Japan).png'),
(25, 'Asteroids', 1979, 2, 1, 5, 'https://tinyurl.com/25q27ckn', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Asteroids%20(bootleg%20on%20Lunar%20Lander%20hardware%2C%20set%201).png'),
(26, 'City Connection', 1982, 3, 1, 11, 'https://tinyurl.com/23ug89n6', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/City%20Connection%20(set%201).png'),
(27, 'Tumble Pop', 1989, 3, 1, 12, 'https://tinyurl.com/284683gz', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Tumble%20Pop%20(bootleg%20set%201).png'),
(28, 'The Tower of Druaga', 1984, 3, 1, 1, 'https://tinyurl.com/2ck4gmh4', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/The%20Tower%20of%20Druaga%20(New%20Ver.).png'),
(29, 'Galaga ''88', 1988, 2, 1, 1, 'https://tinyurl.com/24tgtbzk', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Galaga%20\'88.png'),
(30, 'Dragon Buster', 1984, 3, 1, 1, 'https://tinyurl.com/278arlk2', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Dragon%20Buster.png'),
(31, 'Raiden', 1990, 2, 1, 13, 'https://tinyurl.com/22sqa9os', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Raiden%20(Korea).png'),
(32, 'Nitro Ball', 1992, 2, 1, 12, 'https://tinyurl.com/2ytbzhe5', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Nitro%20Ball%20(World%2C%20set%201).png'),
(33, 'Double Dragon', 1987, 4, 1, 14, 'https://tinyurl.com/24cmzddd', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Double%20Dragon%20(bootleg%20with%20HD6309).png'),
(34, 'Mag Max', 1985, 2, 1, 8, 'https://tinyurl.com/29mlw73y', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Mag%20Max.png'),
(35, 'Sky Kid', 1985, 2, 1, 1, 'https://tinyurl.com/2bohpwuk', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Sky%20Kid%20(CUS60%20version).png'),
(36, 'Grobda', 1984, 2, 1, 1, 'https://tinyurl.com/27nxt5bk', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Grobda%20(New%20Ver.).png'),
(37, 'Snow Bros - Nick & Tom', 1990, 3, 1, 15, 'https://tinyurl.com/277vwxvj', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Snow%20Bros.%20-%20Nick%20%26%20Tom%20(Dooyong%20license).png'),
(38, 'Arkanoid', 1986, 5, 1, 3, 'https://tinyurl.com/2647a5eh', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Arkanoid%20(bootleg%20on%20Block%20hardware).png'),
(39, 'Karnov\'s Revenge', 1994, 4, 1, 12, 'https://tinyurl.com/25gy4haa', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-neogeo-images/master/Named_Snaps/output/Karnov\'s%20Revenge%20_%20Fighter\'s%20History%20Dynamite.png'),
(40, 'BurgerTime', 1982, 3, 1, 12, 'https://tinyurl.com/24tgtbzk', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Burger%20Time%20(Data%20East%20set%201).png'),
(41, 'Avenging Spirit', 1991, 3, 1, 11, 'https://tinyurl.com/23uvz553', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Avenging%20Spirit.png'),
(42, 'Final Fight', 1989, 4, 1, 4, 'https://tinyurl.com/23y78s79', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Final%20Fight%20(900112%20Japan).png'),
(43, 'Son Son', 1984, 3, 1, 4, 'https://tinyurl.com/2xpah9x2', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Son%20Son.png'),
(44, 'Crude Buster', 1990, 3, 1, 12, 'https://tinyurl.com/25qqalof', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Crude%20Buster%20(Japan%20FR%20revision%201).png'),
(46, 'Rod-Land', 1990, 3, 1, 11, 'https://tinyurl.com/2a4yzpdo', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Rod-Land%20(Japan%20bootleg).png'),
(47, 'Don Doko Don', 1982, 3, 1, 3, 'https://tinyurl.com/28gd6pha', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Don%20Doko%20Don%20(Japan).png'),
(48, 'Breakers Revenge', 1996, 4, 1, 16, 'https://tinyurl.com/25fmn6ny', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-neogeo-images/master/Named_Snaps/output/Breakers%20Revenge.png'),
(49, 'Martial Masters', 2001, 4, 1, 17, 'https://tinyurl.com/29q9ydgl', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Martial%20Masters%20(V102%2C%20101%2C%20101%2C%20China).png'),
(50, 'Psycho Soldier', 1986, 3, 1, 18, 'https://tinyurl.com/29wkah34', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Psycho%20Soldier%20(Japan).png'),
(51, 'Nibbler', 1982, 1, 1, 19, 'https://tinyurl.com/2aunscea', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Nibbler%20(Olympia%20-%20rev%208).png'),
(52, 'Shinobi', 1982, 3, 1, 20, 'https://tinyurl.com/22senyz9', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Shinobi%20(beta%20bootleg%2C%20System%2016A).png'),
(53, 'Chain Reaction', 1995, 5, 1, 12, 'https://tinyurl.com/2y2xq52r', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Chain%20Reaction%20(World%2C%20Version%202.2%2C%201995.09.25).png'),
(54, 'New Rally X', 1981, 1, 1, 1, 'https://tinyurl.com/2d5sbzd7', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/New%20Rally%20X.png'),
(55, 'Gunbird', 1994, 2, 1, 21, 'https://tinyurl.com/23rhzo6x', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Gunbird%20(Japan).png'),
(56, 'Aero Fighters', 1992, 2, 1, 22, 'https://tinyurl.com/22sv3mx9', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Aero%20Fighters.png'),
(57, 'DJ Boy', 1989, 3, 1, 23, 'https://tinyurl.com/2y6bmkfk', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/DJ%20Boy%20(Japan%2C%20set%201).png'),
(58, 'Aligator Hunt', 1994, 3, 1, 24, 'https://tinyurl.com/2yk2real', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Alligator%20Hunt%20(Spain%2C%20protected).png'),
(59, 'SD Fighters', 1996, 4, 1, 25, 'https://tinyurl.com/2cqw8dco', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/SD%20Fighters%20(Korea).png'),
(60, 'Qix', 1981, 5, 1, 3, 'https://tinyurl.com/227qj97p', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Qix%20(Rev%202).png'),
(61, 'Crazy Kong', 1981, 3, 1, 26, 'https://tinyurl.com/29mpa8j2', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Crazy%20Kong.png'),
(62, 'Rastan', 1987, 3, 1, 3, 'https://tinyurl.com/2yjl65kn', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Rastan%20(US%20Rev%201).png'),
(63, 'Marvel vs Capcom', 1998, 4, 1, 4, 'https://tinyurl.com/29e73sgz', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Marvel%20vs%20Capcom%20-%20clash%20of%20super%20heroes%20(971222%20USA).png'),
(64, 'Fatal Fury', 1994, 4, 1, 18, 'https://tinyurl.com/2dqqdj3r', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-neogeo-images/master/Named_Snaps/output/Fatal%20Fury%20-%20King%20of%20Fighters%20_%20Garou%20Densetsu%20-%20shukumei%20no%20tatakai%20(Boss%20Hack%20by%20Yumeji).png'),
(65, 'The King of Fighters 98', 1998, 4, 1, 18, 'https://tinyurl.com/26fshker', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-neogeo-images/master/Named_Snaps/output/The%20King%20of%20Fighters%20\'98%20-%20The%20Slugfest%20_%20King%20of%20Fighters%20\'98%20-%20dream%20match%20never%20ends%20(Korean%20board%2C%20set%201).png'),
(66, 'Super Street Fighter II Turbo', 1994, 4, 1, 4, 'https://tinyurl.com/2d788ckr', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Super%20Street%20Fighter%20II%20Turbo%20(super%20street%20fighter%202%20X%20940223%20Asia%20Phoenix%20Edition).png'),
(67, 'Time Pilot', 1982, 2, 1, 6, 'https://tinyurl.com/2aw4jzcx', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Time%20Pilot.png'),
(68, 'Viper Phase 1', 1995, 2, 1, 13, 'https://tinyurl.com/25sxs9px', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Titles/resized/Viper%20Phase%201%20(Germany).png');

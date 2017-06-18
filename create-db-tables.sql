use sandbox;

create table wattage_games (
	id					int(10) not null auto_increment,
	type_slug			varchar(20) not null,
	num_players			int(4) not null default '2',
	date_started 		timestamp not null default current_timestamp,
	date_ended	 		timestamp default null,
	date_last_changed 	timestamp not null default current_timestamp on update current_timestamp,
	primary key (id)
);

-- Ex. insert into wattage_games (type_slug, num_players) values ('checkers', 2);

create table wattage_players (
	id					int(10) not null auto_increment,
	username			varchar(24) default null,
	name				varchar(60) default null,
	email				varchar(120) default null,
	anonymous			boolean default false,
	anon_token			varchar(16) default null,
	date_created 		timestamp not null default current_timestamp,
	date_last_active 	timestamp not null default current_timestamp on update current_timestamp,
	primary key (id)
);

-- Ex. insert into wattage_players (username, name) values ('AnalogKid', 'Anthony Kozar');
-- Ex. insert into wattage_players (anonymous, anon_token) values (true, 'wcjdfoertnm');

-- the gameplayers table associates players with games
create table wattage_gameplayers (
	game_id				int(10) not null,
	player_id			int(10) not null,
	play_order			int(4) not null default '0',
	primary key (game_id, player_id)
);

-- Ex. insert into wattage_gameplayers values (1, 3, 1);

-- How to retrieve the players for a specific game:
-- select * from wattage_players join wattage_gameplayers 
--   on wattage_players.id = wattage_gameplayers.player_id and wattage_gameplayers.game_id = 1
--   order by wattage_gameplayers.play_order;

-- the gamedata table stores serialized objects of classes Game, GameObject, etc.
create table wattage_gamedata (
	id					int(10) not null auto_increment,
	game_id				int(10) not null,
	obj_data			longblob not null,
	primary key (id)
);


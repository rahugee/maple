CREATE TABLE all_reviews AS (
	SELECT `reviewid`, IF( STRCMP( `type` , 'SE' ) , 0, `item` ) AS seriesid, 
		IF( STRCMP( `type` , 'ST' ) , 0, `item` ) AS sid, 
		`chapid` , `uid` , `review` , `date` , `respond`
	FROM `fanfiction_reviews`
);

CREATE TABLE all_ratings AS (
	SELECT `type` , item, IF( STRCMP( `type` , 'SE' ) , NULL , `item` ) AS seriesid, 
		IF( STRCMP( `type` , 'ST' ) , NULL , `item` ) AS sid, 
		`chapid` , `uid` , `reviewer`,round( avg( rating ) ) AS rating
	FROM `fanfiction_reviews`
	GROUP BY seriesid, sid, chapid, uid
);


RENAME TABLE `swimstor_db`.`fanfiction_reviews` TO `swimstor_db`.`fanfiction_reviews_backup` ;


CREATE OR REPLACE VIEW fanfiction_reviews2 AS 
(
select reviewid,item,rw.chapid,rw.uid,rt.reviewer,review,date,respond,type,rating 
from all_reviews rw,all_ratings rt where (rw.uid = rt.uid) AND (rw.chapid = rt.chapid)
)


ALTER TABLE `all_ratings` ADD PRIMARY KEY ( `seriesid` , `sid` , `chapid` , `uid` ) ;


ALTER TABLE `fanfiction_stories` ADD FULLTEXT (
`title` ,
`summary` ,
`storynotes`
);

ALTER TABLE `search_cache` ADD INDEX ( `search_text` ) ;

ALTER TABLE `fanfiction_chapters` ADD FULLTEXT (
`title` ,
`notes` ,
`storytext` ,
`endnotes`
);



CREATE OR REPLACE VIEW `stats_stories` AS select `str`.`sid` AS `sid`,`str`.`count` AS `count`,`str`.`reviews` AS `reviews`,`str`.`rating` AS `rating`,ifnull(`sts`.`favs`,0) AS `favs`,ifnull(`sts`.`shares`,0) AS `shares` from (`fanfiction_stories` `str` left join `fanfiction_stories_stats` `sts` on((`str`.`sid` = `sts`.`sid`)));

CREATE OR REPLACE VIEW `stats_stories` AS select `str`.`sid` AS `sid`,`str`.`count` AS `count`,`str`.`reviews` AS `reviews`,`str`.`rating` AS `rating`,ifnull(`sts`.`favs`,0) AS `favs`,ifnull(`sts`.`shares`,0) AS `shares` from (`fanfiction_stories` `str` left join `fanfiction_stories_stats` `sts` on((`str`.`sid` = `sts`.`sid`)));

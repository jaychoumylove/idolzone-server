
--  start 20200710 17:22:37
--  新建狗4数据库以及记录

 
--  Table structure for f_cfg_pet_skill_four
 
DROP TABLE IF EXISTS `f_cfg_pet_skill_four`;
CREATE TABLE `f_cfg_pet_skill_four` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL COMMENT '技能等级',
  `percent` float(11,3) DEFAULT NULL COMMENT '奖励数额',
  `point` int(11) DEFAULT NULL COMMENT '积分',
  `desc` varchar(255) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lv` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='宠物技能4';

create index f_cfg_pet_skill_four_lv_index
	on f_cfg_pet_skill_four (level);

 
--  Records of f_cfg_pet_skill_four
 
BEGIN;
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (0 , 0 , '冲榜增加额外人气0%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (1 , 0.001 , '冲榜增加额外人气0.1%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (2 , 0.002 , '冲榜增加额外人气0.2%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (3 , 0.003 , '冲榜增加额外人气0.3%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (4 , 0.004 , '冲榜增加额外人气0.4%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (5 , 0.005 , '冲榜增加额外人气0.5%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (6 , 0.006 , '冲榜增加额外人气0.6%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (7 , 0.007 , '冲榜增加额外人气0.7%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (8 , 0.008 , '冲榜增加额外人气0.8%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (9 , 0.009 , '冲榜增加额外人气0.9%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (10 , 0.01 , '冲榜增加额外人气1%' , 1000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (11 , 0.011 , '冲榜增加额外人气1.1%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (12 , 0.012 , '冲榜增加额外人气1.2%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (13 , 0.013 , '冲榜增加额外人气1.3%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (14 , 0.014 , '冲榜增加额外人气1.4%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (15 , 0.015 , '冲榜增加额外人气1.5%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (16 , 0.016 , '冲榜增加额外人气1.6%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (17 , 0.017 , '冲榜增加额外人气1.7%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (18 , 0.018 , '冲榜增加额外人气1.8%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (19 , 0.019 , '冲榜增加额外人气1.9%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (20 , 0.02 , '冲榜增加额外人气2%' , 2000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (21 , 0.021 , '冲榜增加额外人气2.1%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (22 , 0.022 , '冲榜增加额外人气2.2%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (23 , 0.023 , '冲榜增加额外人气2.3%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (24 , 0.024 , '冲榜增加额外人气2.4%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (25 , 0.025 , '冲榜增加额外人气2.5%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (26 , 0.026 , '冲榜增加额外人气2.6%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (27 , 0.027 , '冲榜增加额外人气2.7%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (28 , 0.028 , '冲榜增加额外人气2.8%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (29 , 0.029 , '冲榜增加额外人气2.9%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (30 , 0.03 , '冲榜增加额外人气3%' , 3000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (31 , 0.031 , '冲榜增加额外人气3.1%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (32 , 0.032 , '冲榜增加额外人气3.2%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (33 , 0.033 , '冲榜增加额外人气3.3%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (34 , 0.034 , '冲榜增加额外人气3.4%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (35 , 0.035 , '冲榜增加额外人气3.5%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (36 , 0.036 , '冲榜增加额外人气3.6%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (37 , 0.037 , '冲榜增加额外人气3.7%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (38 , 0.038 , '冲榜增加额外人气3.8%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (39 , 0.039 , '冲榜增加额外人气3.9%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (40 , 0.04 , '冲榜增加额外人气4%' , 4000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (41 , 0.041 , '冲榜增加额外人气4.1%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (42 , 0.042 , '冲榜增加额外人气4.2%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (43 , 0.043 , '冲榜增加额外人气4.3%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (44 , 0.044 , '冲榜增加额外人气4.4%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (45 , 0.045 , '冲榜增加额外人气4.5%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (46 , 0.046 , '冲榜增加额外人气4.6%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (47 , 0.047 , '冲榜增加额外人气4.7%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (48 , 0.048 , '冲榜增加额外人气4.8%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (49 , 0.049 , '冲榜增加额外人气4.9%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (50 , 0.05 , '冲榜增加额外人气5%' , 5000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (51 , 0.051 , '冲榜增加额外人气5.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (52 , 0.052 , '冲榜增加额外人气5.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (53 , 0.053 , '冲榜增加额外人气5.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (54 , 0.054 , '冲榜增加额外人气5.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (55 , 0.055 , '冲榜增加额外人气5.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (56 , 0.056 , '冲榜增加额外人气5.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (57 , 0.057 , '冲榜增加额外人气5.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (58 , 0.058 , '冲榜增加额外人气5.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (59 , 0.059 , '冲榜增加额外人气5.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (60 , 0.06 , '冲榜增加额外人气6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (61 , 0.061 , '冲榜增加额外人气6.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (62 , 0.062 , '冲榜增加额外人气6.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (63 , 0.063 , '冲榜增加额外人气6.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (64 , 0.064 , '冲榜增加额外人气6.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (65 , 0.065 , '冲榜增加额外人气6.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (66 , 0.066 , '冲榜增加额外人气6.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (67 , 0.067 , '冲榜增加额外人气6.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (68 , 0.068 , '冲榜增加额外人气6.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (69 , 0.069 , '冲榜增加额外人气6.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (70 , 0.07 , '冲榜增加额外人气7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (71 , 0.071 , '冲榜增加额外人气7.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (72 , 0.072 , '冲榜增加额外人气7.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (73 , 0.073 , '冲榜增加额外人气7.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (74 , 0.074 , '冲榜增加额外人气7.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (75 , 0.075 , '冲榜增加额外人气7.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (76 , 0.076 , '冲榜增加额外人气7.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (77 , 0.077 , '冲榜增加额外人气7.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (78 , 0.078 , '冲榜增加额外人气7.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (79 , 0.079 , '冲榜增加额外人气7.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (80 , 0.08 , '冲榜增加额外人气8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (81 , 0.081 , '冲榜增加额外人气8.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (82 , 0.082 , '冲榜增加额外人气8.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (83 , 0.083 , '冲榜增加额外人气8.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (84 , 0.084 , '冲榜增加额外人气8.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (85 , 0.085 , '冲榜增加额外人气8.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (86 , 0.086 , '冲榜增加额外人气8.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (87 , 0.087 , '冲榜增加额外人气8.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (88 , 0.088 , '冲榜增加额外人气8.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (89 , 0.089 , '冲榜增加额外人气8.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (90 , 0.09 , '冲榜增加额外人气9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (91 , 0.091 , '冲榜增加额外人气9.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (92 , 0.092 , '冲榜增加额外人气9.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (93 , 0.093 , '冲榜增加额外人气9.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (94 , 0.094 , '冲榜增加额外人气9.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (95 , 0.095 , '冲榜增加额外人气9.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (96 , 0.096 , '冲榜增加额外人气9.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (97 , 0.097 , '冲榜增加额外人气9.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (98 , 0.098 , '冲榜增加额外人气9.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (99 , 0.099 , '冲榜增加额外人气9.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (100 , 0.1 , '冲榜增加额外人气10%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (101 , 0.101 , '冲榜增加额外人气10.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (102 , 0.102 , '冲榜增加额外人气10.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (103 , 0.103 , '冲榜增加额外人气10.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (104 , 0.104 , '冲榜增加额外人气10.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (105 , 0.105 , '冲榜增加额外人气10.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (106 , 0.106 , '冲榜增加额外人气10.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (107 , 0.107 , '冲榜增加额外人气10.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (108 , 0.108 , '冲榜增加额外人气10.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (109 , 0.109 , '冲榜增加额外人气10.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (110 , 0.11 , '冲榜增加额外人气11%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (111 , 0.111 , '冲榜增加额外人气11.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (112 , 0.112 , '冲榜增加额外人气11.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (113 , 0.113 , '冲榜增加额外人气11.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (114 , 0.114 , '冲榜增加额外人气11.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (115 , 0.115 , '冲榜增加额外人气11.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (116 , 0.116 , '冲榜增加额外人气11.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (117 , 0.117 , '冲榜增加额外人气11.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (118 , 0.118 , '冲榜增加额外人气11.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (119 , 0.119 , '冲榜增加额外人气11.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (120 , 0.12 , '冲榜增加额外人气12%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (121 , 0.121 , '冲榜增加额外人气12.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (122 , 0.122 , '冲榜增加额外人气12.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (123 , 0.123 , '冲榜增加额外人气12.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (124 , 0.124 , '冲榜增加额外人气12.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (125 , 0.125 , '冲榜增加额外人气12.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (126 , 0.126 , '冲榜增加额外人气12.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (127 , 0.127 , '冲榜增加额外人气12.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (128 , 0.128 , '冲榜增加额外人气12.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (129 , 0.129 , '冲榜增加额外人气12.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (130 , 0.13 , '冲榜增加额外人气13%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (131 , 0.131 , '冲榜增加额外人气13.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (132 , 0.132 , '冲榜增加额外人气13.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (133 , 0.133 , '冲榜增加额外人气13.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (134 , 0.134 , '冲榜增加额外人气13.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (135 , 0.135 , '冲榜增加额外人气13.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (136 , 0.136 , '冲榜增加额外人气13.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (137 , 0.137 , '冲榜增加额外人气13.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (138 , 0.138 , '冲榜增加额外人气13.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (139 , 0.139 , '冲榜增加额外人气13.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (140 , 0.14 , '冲榜增加额外人气14%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (141 , 0.141 , '冲榜增加额外人气14.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (142 , 0.142 , '冲榜增加额外人气14.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (143 , 0.143 , '冲榜增加额外人气14.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (144 , 0.144 , '冲榜增加额外人气14.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (145 , 0.145 , '冲榜增加额外人气14.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (146 , 0.146 , '冲榜增加额外人气14.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (147 , 0.147 , '冲榜增加额外人气14.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (148 , 0.148 , '冲榜增加额外人气14.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (149 , 0.149 , '冲榜增加额外人气14.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (150 , 0.15 , '冲榜增加额外人气15%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (151 , 0.151 , '冲榜增加额外人气15.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (152 , 0.152 , '冲榜增加额外人气15.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (153 , 0.153 , '冲榜增加额外人气15.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (154 , 0.154 , '冲榜增加额外人气15.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (155 , 0.155 , '冲榜增加额外人气15.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (156 , 0.156 , '冲榜增加额外人气15.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (157 , 0.157 , '冲榜增加额外人气15.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (158 , 0.158 , '冲榜增加额外人气15.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (159 , 0.159 , '冲榜增加额外人气15.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (160 , 0.16 , '冲榜增加额外人气16%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (161 , 0.161 , '冲榜增加额外人气16.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (162 , 0.162 , '冲榜增加额外人气16.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (163 , 0.163 , '冲榜增加额外人气16.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (164 , 0.164 , '冲榜增加额外人气16.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (165 , 0.165 , '冲榜增加额外人气16.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (166 , 0.166 , '冲榜增加额外人气16.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (167 , 0.167 , '冲榜增加额外人气16.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (168 , 0.168 , '冲榜增加额外人气16.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (169 , 0.169 , '冲榜增加额外人气16.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (170 , 0.17 , '冲榜增加额外人气17%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (171 , 0.171 , '冲榜增加额外人气17.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (172 , 0.172 , '冲榜增加额外人气17.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (173 , 0.173 , '冲榜增加额外人气17.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (174 , 0.174 , '冲榜增加额外人气17.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (175 , 0.175 , '冲榜增加额外人气17.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (176 , 0.176 , '冲榜增加额外人气17.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (177 , 0.177 , '冲榜增加额外人气17.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (178 , 0.178 , '冲榜增加额外人气17.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (179 , 0.179 , '冲榜增加额外人气17.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (180 , 0.18 , '冲榜增加额外人气18%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (181 , 0.181 , '冲榜增加额外人气18.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (182 , 0.182 , '冲榜增加额外人气18.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (183 , 0.183 , '冲榜增加额外人气18.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (184 , 0.184 , '冲榜增加额外人气18.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (185 , 0.185 , '冲榜增加额外人气18.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (186 , 0.186 , '冲榜增加额外人气18.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (187 , 0.187 , '冲榜增加额外人气18.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (188 , 0.188 , '冲榜增加额外人气18.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (189 , 0.189 , '冲榜增加额外人气18.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (190 , 0.19 , '冲榜增加额外人气19%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (191 , 0.191 , '冲榜增加额外人气19.1%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (192 , 0.192 , '冲榜增加额外人气19.2%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (193 , 0.193 , '冲榜增加额外人气19.3%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (194 , 0.194 , '冲榜增加额外人气19.4%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (195 , 0.195 , '冲榜增加额外人气19.5%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (196 , 0.196 , '冲榜增加额外人气19.6%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (197 , 0.197 , '冲榜增加额外人气19.7%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (198 , 0.198 , '冲榜增加额外人气19.8%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (199 , 0.199 , '冲榜增加额外人气19.9%' , 10000000);
INSERT INTO `f_cfg_pet_skill_four` (`level` , `percent` , `desc` , `point`) VALUES (200 , 0.2 , '冲榜增加额外人气20%' , 10000000);
COMMIT;

--  end

--  start 用户精灵新增4技能字段 20200710 17:32:47
alter table f_user_sprite
	add skill_four_level int default 0 not null comment '宠物4技能等级';
--  end


--  start 八一建军节开屏活动 20200714 16:56:23

alter table f_open
	add type enum('normal', '81soldier') default 'normal' not null after hot;

create index f_open_star_index
	on f_open (star_id);

create index f_open_type_index
	on f_open (type);

create index f_open_user_id_index
	on f_open (user_id);

create index f_open_hot_index
	on f_open (hot);

-- vote_end 投票截止时间
INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`) VALUES ('开屏活动配置', 'open', '{\"current\":\"81soldier\",\"content\":{\"81soldier\":{\"banner\":[{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Fc6k4icARtLOpP2kdnMiaic0oQ1WHvWgXgCwVdUicCm07knFnb0xMPy1VFqAYptzPbiavX9z49wJZ6mKw/0\",\"vote_end\":1595399400},{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Fc6k4icARtLOpP2kdnMiaic0oGemvqN43zUUia0ftswfBZ0Ch4kjiblryV4BK4Pb8DJBiagibyUWlGtD4BA/0\",\"vote_end\":1596297000},{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Fc6k4icARtLOpP2kdnMiaic0okiada0VNuHsSRGdnJoQyWbgvicod7pDo2wVjnwkUntbmHnwCAJXHv9ag/0\",\"vote_end\":1596384000}],\"help\":{\"article\":1,\"label\":\"活动说明\"},\"time\":{\"end\":\"2020-07-27\"}}}}', 1);

--  end

--  start 福袋活动 20200717 10:07:34

 
--  Table structure for f_cfg_weal_activity_task
 
DROP TABLE IF EXISTS `f_cfg_weal_activity_task`;
CREATE TABLE `f_cfg_weal_activity_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `btn_text` varchar(255) DEFAULT '' COMMENT '未完成时的按钮文字',
  `gopage` varchar(255) DEFAULT NULL COMMENT '未完成时的跳转页面',
  `open_type` varchar(255) DEFAULT NULL COMMENT '调用button组件的open-type',
  `shareid` int(11) unsigned DEFAULT '0' COMMENT 'cfg_share表中的id',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='任务福袋活动';

 
--  Table structure for f_rec_weal_activity
 
DROP TABLE IF EXISTS `f_rec_weal_activity`;
CREATE TABLE `f_rec_weal_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `content` varchar(300) DEFAULT NULL COMMENT '日志内容',
  `blessing_num` bigint(20) DEFAULT '0' COMMENT '增加福袋',
  `lucky_value` bigint(20) DEFAULT '0' COMMENT '增加幸运值',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`user_id`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE,
  KEY `content` (`content`(191)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='日志';

 
--  Table structure for f_rec_weal_activity_task
 
DROP TABLE IF EXISTS `f_rec_weal_activity_task`;
CREATE TABLE `f_rec_weal_activity_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `done_times` bigint(20) DEFAULT '0' COMMENT '已完成次数',
  `is_settle_times` int(11) DEFAULT '0' COMMENT '已领奖励次数',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_id` (`task_id`,`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='记录-任务';

UPDATE `f_cfg` SET `value` = '{\"farm\":[{\"name\":\"\",\"path\":\"\",\"icon\":\"\"}],\"group\":[{\"name\":\"618狂欢\",\"path\":\"/pages/active/active618\",\"icon\":\"/static/image/activity/activity_item.png\",\"start_time\":\"2020/06/07 00:00:00\",\"end_time\":\"2020/06/23 00:00:00\",\"status\":1},{\"name\":\"夏日福袋\",\"path\":\"/pages/active/weal\",\"icon\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Ey1LXtlaYC5q9Mz4h6ib9egnl0WTyzE1zYtseY4GuxHBDmW0iaUUZUKXLKvuRibmUvSGtHJpa4RKZXw/0\",\"start_time\":\"2020/07/01 00:00:00\",\"end_time\":\"2020/07/27 00:00:00\",\"status\":1},{\"name\":\"开屏备选\",\"path\":\"/pages/open/rank\",\"icon\":\"https://mmbiz.qpic.cn/mmbiz_png/h9gCibVJa7JXhkB7555NdBfHhiaEUA9UPrbX9x4xbK7jafjDcgBHPfiamk4e8VHUZdoEQntLXXpVgrxTktoS0zLwQ/0\",\"start_time\":\"2020/07/01 00:00:00\",\"end_time\":\"2020/07/27 00:00:00\",\"status\":1}],\"user\":[{\"name\":\"任务\",\"path\":\"/pages/task/task\",\"icon\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxg8poPGbPbBhXGBtzqccQicFtFiaMzq8O2yB0fVKsIziaJNSFR5c56g8lw/0\"}]}' WHERE `key` = 'btn_cfg';

alter table f_cfg_weal_activity_task
	add done bigint default 0 not null comment '完成任务所需要的数值' after sort;

alter table f_cfg_weal_activity_task
	add limit_times int default 0 not null comment '完成任务的每天的上限' after done;

alter table f_cfg_weal_activity_task
	add `key` varchar(255) null comment '任务key' after limit_times;

alter table f_cfg_weal_activity_task
	add reward float(13,2) default 0.10 not null comment '奖励' after `key`;

alter table f_cfg_weal_activity_task
	add type enum('SUM', 'DAY') default 'SUM' not null comment '累计任务
每日任务' after reward;

create index f_cfg_weal_activity_task_key_index
	on f_cfg_weal_activity_task (`key`);

create index f_cfg_weal_activity_task_type_index
	on f_cfg_weal_activity_task (type);

ALTER TABLE `f_user_ext`
ADD COLUMN `bag_num` int(10) NOT NULL DEFAULT 0 COMMENT '福袋数量' AFTER `delete_time`,
ADD COLUMN `lucky` int(10) NOT NULL DEFAULT 5 COMMENT '幸运值' AFTER `bag_num`,
ADD COLUMN `send_hot` bigint(20) NOT NULL DEFAULT 0 COMMENT '使用福袋额外获得的hot' AFTER `lucky`,
ADD COLUMN `is_receive` tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否领取了公众号福利' AFTER `send_hot`;

ALTER TABLE `f_rec_weal_activity`
CHANGE COLUMN `blessing_num` `bag_num` bigint(20) NULL DEFAULT 0 COMMENT '增加福袋' AFTER `content`,
CHANGE COLUMN `lucky_value` `lucky` bigint(20) NULL DEFAULT 0 COMMENT '增加幸运值' AFTER `bag_num`;

ALTER TABLE `f_user_ext`
CHANGE COLUMN `send_hot` `send_weal_hot` bigint(20) NOT NULL DEFAULT 0 COMMENT '使用福袋额外获得的hot' AFTER `lucky`,
CHANGE COLUMN `is_receive` `weal_receive` tinyint(3) NOT NULL DEFAULT 0 COMMENT '是否领取了公众号福利' AFTER `send_weal_hot`;

alter table f_user_ext modify send_weal_hot bigint default 0 not null comment '使用福袋额外获得的hot';

alter table f_user_ext modify lucky float(5,2) default 0.00 not null;

alter table f_rec_weal_activity modify lucky float(5,2) default 0.00 null comment '增加幸运值';

alter table f_cfg_weal_activity_task
	add status enum('ON', 'OFF') default 'ON' not null;

alter table f_cfg_weal_activity_task modify type enum('SUM', 'DAY', 'ONCE') default 'SUM' not null comment '累计任务
每日任务
单次任务';

-- 福袋任务数据
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('邀请好友', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Fy1abNSLpfp8oqM7wyicwdCLBXKMv4837p05e2Wg6yDHoosSjSSs8g2SX5YXN7Ntsic3pDrB9espgA/0', '每成功邀请好友', 7, 10, 0, 'INVITE', 0.10, 'SUM', '去邀人', NULL, 'share', 0, '2020-06-11 21:44:19', '2020-07-20 15:45:36', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('粉丝团集结', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FquzibbuL4TMQyMbRKaQyUhDJsEo44nQasM1kA1rDUsknA3svibiakkq0z4Dn4l7kVoRte5gfTyAickw/0', '每次完成集结', 5, 10, 0, 'FANS_CLUB_MASS', 0.10, 'SUM', '去集结', '/pages/fans/fans_list', '', 0, '2020-06-11 21:44:19', '2020-07-20 15:45:36', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('贡献人气', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9G3d5wQSx6E5w4aNVVRmUp2KdEBGSKOMnicH9DAMlajZszybNCdEicTtBfLbgXbbrX7nhibKPg5GWXoQ/0', '每次贡献', 1, 1000000, 0, 'SUM_COUNT', 0.10, 'SUM', '去打榜', '/pages/group/group', NULL, 0, '2020-06-11 21:44:19', '2020-07-21 11:19:35', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('微博超话任务', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FquzibbuL4TMQyMbRKaQyUhOPqUgXsaTU7dGU4Omu0iaxE5MuQa9vJcj7gY0FWsaqMdiakPeAWWdMgQ/0', '一天完成一次', 8, 1, 1, 'WEIBO_SUPER', 0.10, 'DAY', '', NULL, NULL, 0, '2020-06-13 14:47:27', '2020-07-21 11:19:35', NULL, 'OFF');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('微博转发任务', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FquzibbuL4TMQyMbRKaQyUh7aa4DoB9wuobMCJxzDORiafRLAhxBJUSwJsEKYhyS1efxuhXDpEV2Cg/0', '一天完成一次', 9, 1, 1, 'WEIBO_RE_POST', 0.10, 'DAY', '', NULL, NULL, 0, '2020-06-13 14:47:39', '2020-07-21 11:19:35', NULL, 'OFF');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('使用鲜花', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0', '每次冲榜使用鲜花', 2, 1000000, 0, 'USE_FOLLOWER', 0.10, 'SUM', '去打榜', '/pages/group/group', NULL, 0, '2020-07-20 09:30:02', '2020-07-21 09:42:14', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('使用钻石', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERibO7VvqicUHiaSaSa5xyRcvuiaOibBLgTdh8Mh4csFEWRCbz3VIQw1VKMCQ/0', '每次使用钻石', 3, 100, 0, 'USE_STONE', 0.10, 'SUM', '', NULL, NULL, 0, '2020-07-20 09:30:44', '2020-07-20 15:45:36', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('使用积分', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxaonPdq5wuw0mcjvxg7fiaH9U9f5HX3D4VTVJibsHHf8MB4C2nAIELfog/0', '每次使用积分', 4, 10000000, 0, 'USE_POINT', 0.10, 'SUM', '', NULL, NULL, 0, '2020-07-20 09:36:09', '2020-07-21 11:19:35', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('补充鲜花', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FquzibbuL4TMQyMbRKaQyUhLBDFLSfEgnRuQZBDwBQHBqr9Y4tMicSDcDjzwWhC4Xh0z19SmBaX2rg/0', '每次补充鲜花', 6, 35, 0, 'RECHARGE', 0.10, 'SUM', '回复“1”', '/pages/charge/charge', 'contact', 0, '2020-07-20 09:37:16', '2020-07-21 11:19:35', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('等级达成', '', NULL, 10, 0, 1, 'LEVEL', 0.10, 'ONCE', '', NULL, NULL, 0, '2020-07-21 13:44:34', '2020-07-21 15:12:04', NULL, 'ON');
INSERT INTO `f_cfg_weal_activity_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`) VALUES ('壕徽章', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9G95njnZp6t7hkcfsoraFhyFkjhRwv6OG00pSKo7DLXZAUibrL8SldBmf7kdCFB1icsWHxc0n34AGrA/0', '', 11, 0, 1, 'BADGE2', 0.10, 'ONCE', '', NULL, NULL, 0, '2020-07-21 15:12:04', '2020-07-21 18:30:00', NULL, 'ON');

alter table f_user_ext alter column lucky set default 0.1;

alter table f_open_top
    add hot bigint default 0 not null;

alter table f_open_top drop key date;

--  end



--  v3 start

--  start 占领新增占领时间
alter table f_rec_hour
	add top_time int default 0 not null;
--  end

--  start 新增道具获取方式
alter table f_prop
	add get_type enum('STORE', 'CHARGE', 'TASK') default 'STORE' not null;

create index f_prop_type_index
	on f_prop (get_type);

alter table f_prop
    add `key` varchar(150) null

create index f_prop_key_index
    on f_prop (`key`)
--  end


alter table f_user_ext
	add scrap bigint default 0 not null;

--  抽奖配置表
create table f_cfg_lucky_draw
(
    id          int auto_increment,
    title       varchar(255)                        null comment '奖池名称 如第一期奖池',
    reward      text                                null comment ' [{
        "flower": 500,
        "name": "鲜花",
        "weights": 8,
        "type": "currery",
      	"index": 6,
  }]',
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp default CURRENT_TIMESTAMP not null,
    delete_time timestamp                           null,
    current     tinyint   default 0                 not null comment '是否当前奖池',
    draw        varchar(255)                        null comment '抽奖图片uri',
    constraint f_cfg_lottery_id_uindex
        unique (id)
)
    comment '幸运奖池';

create index f_cfg_lottery_current_index
    on f_cfg_lucky_draw (current);

alter table f_cfg_lucky_draw
    add primary key (id);

--  充值礼包
create table f_cfg_paid
(
    id          int auto_increment,
    title       varchar(255)                                  null,
    type        enum ('DAY', 'SUM') default 'SUM'             not null comment '类型',
    `key`       varchar(150)                                  null comment '方便查找key',
    count       float(16, 2)        default 0.00              not null comment '达到金额',
    status      enum ('ON', 'OFF')  default 'ON'              not null,
    create_time timestamp           default CURRENT_TIMESTAMP not null,
    update_time timestamp           default CURRENT_TIMESTAMP not null,
    delete_time timestamp                                     null,
    reward      text                                          null comment '奖励json',
    constraint f_cfg_paid_id_uindex
        unique (id),
    constraint f_cfg_paid_key_index
        unique (`key`)
)
    comment '用户充值奖励';

create index f_cfg_paid_st_index
    on f_cfg_paid (status, type);

create index f_cfg_paid_status_index
    on f_cfg_paid (status);

create index f_cfg_paid_type_index
    on f_cfg_paid (type);

alter table f_cfg_paid
    add primary key (id);

--  公益打卡
create table f_cfg_welfare
(
    id          int auto_increment,
    title       varchar(255)                        not null,
    `desc`      varchar(255)                        not null,
    notice      text                                null,
    end         int                                 not null comment '结束时间-时间戳',
    extra       text                                not null comment '附加',
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp default CURRENT_TIMESTAMP not null,
    delete_time timestamp                           null,
    type        varchar(255)                        null comment '打卡类型',
    go_notice   int                                 null,
    constraint f_cfg_welfare_id_uindex
        unique (id)
)
    comment '公益打卡表配置';

create index f_cfg_welfare_end_index
    on f_cfg_welfare (end);

alter table f_cfg_welfare
    add primary key (id);


create table f_rec_lucky_draw_log
(
    id          int auto_increment,
    lucky_draw  int                                 not null comment '抽奖池id',
    user_id     int                                 not null,
    item        text                                null comment '抽取到的奖品',
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp default CURRENT_TIMESTAMP not null,
    delete_time timestamp                           null,
    constraint f_rec_lucky_draw_log_id_uindex
        unique (id)
)
    comment '抽奖记录';

create index f_rec_lucky_draw_log_u_index
    on f_rec_lucky_draw_log (user_id);

alter table f_rec_lucky_draw_log
    add primary key (id);


create table f_welfare
(
    id          int auto_increment,
    star_id     int                                 not null,
    welfare     int                                 not null comment 'cfg_welfare主键',
    count       bigint    default 0                 not null,
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp default CURRENT_TIMESTAMP not null,
    delete_time timestamp                           null,
    constraint f_welfare_id_uindex
        unique (id)
)
    comment '公益打榜表';

create index f_welfare_star_index
    on f_welfare (star_id);

create index f_welfare_sw_index
    on f_welfare (star_id, welfare);

create index f_welfare_wel_index
    on f_welfare (welfare);

alter table f_welfare
    add primary key (id);

create table f_welfare_user
(
    id          int auto_increment,
    user_id     int                                 not null,
    welfare     int                                 not null comment 'welfare表主键',
    count       bigint    default 0                 not null,
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp default CURRENT_TIMESTAMP not null,
    delete_time timestamp                           null,
    constraint f_welfare_user_id_uindex
        unique (id)
)
    comment '打卡用户记录';

create index f_welfare_user_user_index
    on f_welfare_user (user_id);

create index f_welfare_user_wel_index
    on f_welfare_user (welfare);

create index f_welfare_user_welus_index
    on f_welfare_user (user_id, welfare);

alter table f_welfare_user
    add primary key (id);

create table f_rec_user_paid
(
    id          int auto_increment,
    paid_type   enum ('DAY', 'SUM') default 'SUM'             not null,
    user_id     int                                           not null,
    count       float(16, 2)        default 0.00              not null,
    create_time timestamp           default CURRENT_TIMESTAMP not null,
    update_time timestamp           default CURRENT_TIMESTAMP not null,
    delete_time timestamp                                     null,
    is_settle   int                 default 0                 not null,
    constraint f_rec_user_paid_id_uindex
        unique (id)
)
    comment '用户记录表';

create index f_rec_user_paid_type_index
    on f_rec_user_paid (paid_type);

create index f_rec_user_paid_up_index
    on f_rec_user_paid (user_id, paid_type);

create index f_rec_user_paid_user_index
    on f_rec_user_paid (user_id);

alter table f_rec_user_paid
    add primary key (id);

create table f_rec_user_paid_log
(
    id          int auto_increment,
    user_id     int                                           not null,
    item        text                                          null,
    create_time timestamp           default CURRENT_TIMESTAMP not null,
    update_time timestamp           default CURRENT_TIMESTAMP not null,
    delete_time timestamp                                     null,
    paid_type   enum ('DAY', 'SUM') default 'SUM'             not null,
    title       varchar(255)                                  null,
    paid        int                 default 0                 not null,
    constraint f_rec_user_paid_log_id_uindex
        unique (id)
)
    comment '领取充值奖励表';

create index f_rec_user_paid_log_opa_index
    on f_rec_user_paid_log (paid_type);

create index f_rec_user_paid_log_up_index
    on f_rec_user_paid_log (user_id, paid_type);

create index f_rec_user_paid_loguser_index
    on f_rec_user_paid_log (user_id);

alter table f_rec_user_paid_log
    add primary key (id);


create table f_user_scrap
(
    id            int auto_increment,
    scrap_id      int                                 not null,
    user_id       int                                 not null,
    exchange      int       default 0                 not null,
    exchange_time int                                 null,
    create_time   timestamp default CURRENT_TIMESTAMP not null,
    update_time   timestamp default CURRENT_TIMESTAMP not null,
    delete_time   timestamp                           null,
    constraint f_user_scrap_id_uindex
        unique (id)
)
    comment '用户碎片';

create index f_user_scrap_scrap_index
    on f_user_scrap (scrap_id);

create index f_user_scrap_us_index
    on f_user_scrap (user_id, scrap_id);

create index f_user_scrap_user_index
    on f_user_scrap (user_id);

alter table f_user_scrap
    add primary key (id);

create table f_cfg_scrap
(
    id              int auto_increment,
    name            varchar(255)                                   not null,
    `key`           varchar(150)                                   not null,
    `desc`          varchar(255)                                   null,
    count           int                  default 0                 not null comment '需要集齐的数量',
    limit_exchange  int                  default 0                 not null comment '限制兑换数量
0 无限制',
    exchange_number int                  default 0                 not null comment '已兑换的数量',
    type            enum ('DAY', 'WEEK') default 'WEEK'            not null comment '类型
day 每日碎片
week 每周碎片',
    status          enum ('ON', 'OFF')   default 'ON'              not null comment '状态',
    image_s         varchar(255)                                   not null comment '碎片图片',
    image_l         varchar(255)                                   not null comment '碎片合成之后的图片',
    create_time     timestamp            default CURRENT_TIMESTAMP not null,
    update_time     timestamp            default CURRENT_TIMESTAMP not null,
    delete_time     timestamp                                      null,
    extra           text                                           null,
    constraint f_cfg_scrap_id_uindex
        unique (id),
    constraint f_cfg_scrap_key_index
        unique (`key`)
)
    comment '碎片';

create index f_cfg_scrap_status_index
    on f_cfg_scrap (status);

create index f_cfg_scrap_ts_index
    on f_cfg_scrap (type, status);

create index f_cfg_scrap_type_index
    on f_cfg_scrap (type);

alter table f_cfg_scrap
    add primary key (id);

alter table f_prop
	add `key` varchar(150) null;

alter table f_prop
	add get_type enum('STORE', 'CHARGE', 'TASK') default 'STORE' not null;

create index f_prop_key_index
	on f_prop (`key`);

INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('活动中心', 'active_conform', '{\"banner\":[{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FGIpxZsgiahv3AiauB06MOEqKmDId1DMfYwI9wWLGzUFKpg6KQs0HQCkYd5n6OEPr7fpK4t8ZtRlTg/0\",\"gopage\":\"/pages/welfare/welfare\"}],\"active\":[{\"head\":{\"title\":\"每日精彩活动\",\"page_desc\":\"活动说明>\",\"gopage\":\"/pages/notice/notice?id=1\"},\"list\":[{\"img\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FGIpxZsgiahv3AiauB06MOEqIpXObxnKhuMAUWWxjbN4QtJyRiaBhNDicy8e8JiarnYkNXr0K6o3wkOgw/0\",\"title\":\"每日福利\",\"desc\":\"100%中奖\",\"status\":\"NORMAL\",\"gopage\":\"/pages/lucky/lucky\",\"shareid\":\"\",\"tip\":\"NEW\",\"open_type\":\"\",\"btn_text\":\"立即参与\",\"btn_class\":\"hot btn-s\"},{\"img\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FGIpxZsgiahv3AiauB06MOEq36rnBbm57KtNWTBQor2vCl7RSocCtb4b59uVzOpjQWLPKRZ4E7ia54g/0\",\"title\":\"占领封面\",\"desc\":\"助力爱豆\",\"status\":\"NORMAL\",\"gopage\":\"/pages/index/fengyun\",\"shareid\":\"\",\"tip\":\"HOT\",\"open_type\":\"\",\"btn_text\":\"立即参与\",\"btn_class\":\"hot btn-s\"},{\"img\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EGbxgC4CjR5wKtNQuKiaDnrSibVnxo0Xj1f435iaTTmStDN9Roojib89LNwXzqfTeqoicdcEuKPo7ktqg/0\",\"title\":\"团战PK\",\"desc\":\"超多奖励\",\"gopage\":\"/pages/pk/pk_index\",\"shareid\":\"\",\"tip\":\"HOT\",\"status\":\"NORMAL\",\"open_type\":\"\",\"btn_text\":\"立即参与\",\"btn_class\":\"hot btn-s\"}],\"type\":\"flex\"},{\"head\":{\"title\":\"领取更多人气\",\"page_desc\":\"玩法说明>\",\"gopage\":\"https://mp.weixin.qq.com/s/V-Zw-FDPKLKY4GJfBdZS7w\"},\"list\":[{\"img\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FGIpxZsgiahv3AiauB06MOEqjrSzNqo8ib55X0zAQNb3ryGdnGhJhictrcA5LpByI8LqpsLR9xo12teg/0\",\"title\":\"我的农场\",\"desc\":\"大量免费金豆\",\"status\":\"NORMAL\",\"gopage\":\"/pages/farm/farm\",\"shareid\":\"\",\"tip\":\"\",\"open_type\":\"\",\"btn_text\":\"前往领取\",\"btn_class\":\"normal btn-m\"},{\"img\":\"https://mmbiz.qpic.cn/mmbiz_gif/w5pLFvdua9GnD8mrIKwSEItXUhLNibPUxrL7Iia1H7HDGzuIlPlI2FdzzTsxsbYmI6NSibzg6QbO5Ekm3srmD8Ltw/0\",\"title\":\"我的宠物\",\"desc\":\"获取额外收益\",\"status\":\"WAIT\",\"gopage\":\"\",\"shareid\":\"\",\"tip\":\"\",\"open_type\":\"\",\"btn_text\":\"敬请期待\",\"btn_class\":\"normal btn-m\"}],\"type\":\"flex\"},{\"head\":{\"title\":\"福利活动\",\"page_desc\":\"往期福利发放>\",\"gopage\":\"\"},\"list\":[{\"img\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9HKFm38Dv98TP2KyPl7wR9Q5CwBdbIkoEf165gd5tWgPOAKFUA0p9PrpEj1WOrsN7FNatXHicHXCaw/0\",\"title\":\"周榜/月榜福利\",\"desc\":\"大屏+应援金\",\"gopage\":\"/pages/notice/notice?id=1\",\"shareid\":\"\",\"tip\":\"NEW\",\"open_type\":\"\",\"btn_text\":\"查看福利\",\"btn_class\":\"normal btn-m\"},{\"img\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9HKFm38Dv98TP2KyPl7wR9QgQEWb4lD0wdklUib37kJMib6g13S2VAmibUzS3FR0vA8KLCsJ4QkPZbZw/0\",\"title\":\"打卡解锁福利\",\"desc\":\"大屏+应援金\",\"gopage\":\"/pages/active_one/active_one_list\",\"shareid\":\"\",\"tip\":\"NEW\",\"open_type\":\"\",\"btn_text\":\"立即参与\",\"btn_class\":\"normal btn-m\"}],\"type\":\"flex\"}]}', 1, '2020-07-28 14:23:17', '2020-08-04 13:49:41', NULL);
INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('充值福利', 'recharge_lucky', '{\"day_first_charge\":{\"title\":\"每日首次补充鲜花、钻石礼包\",\"desc\":\"补充任意数值（≥35）即可领取超值礼包\",\"btn_text\":\"领取补充鲜花、钻石的礼包\"},\"sum_charge\":{\"title\":\"补充鲜花、钻石领取每日福利\",\"desc\":\"领取每日累计补充鲜花、钻石的奖励，每日首次领取抽奖券x2\",\"lucky\":{\"title\":\"本周奖品\",\"tip\":\"中奖纪录\"}},\"scrap\":{\"title\":\"集齐幸运碎片获得相应的奖品\",\"desc\":\"活动时间：8月10日——8月16日\"}}', 1, '2020-07-30 14:35:35', '2020-08-04 17:01:32', NULL);
INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('占领封面', 'occupy_notice', '{\"title\":\"活动说明\",\"content\":[\"1、在一小时内，贡献鲜花第一的粉丝可以助力爱豆占领封面\",\"2、鲜花小时榜的贡献值每小时清零，重新计算数值占领封面\",\"3、爱豆首页封面图由各圈领袖粉上传，尺寸649X247\",\"4、无领袖粉请加客服申请\"]}', 1, '2020-08-03 11:44:27', '2020-08-03 11:48:16', NULL);

INSERT INTO `f_cfg_share`(`desc`, `title`, `imageUrl`, `path`, `create_time`, `update_time`, `delete_time`) VALUES ('钻石公益', '快来参加STARNAME100万钻石公益解锁活动', NULL, 'path=/pages/welfare/welfare', '2020-08-04 14:56:29', '2020-08-04 14:56:29', NULL);
INSERT INTO `f_cfg_welfare`(`title`, `desc`, `notice`, `end`, `extra`, `create_time`, `update_time`, `delete_time`, `type`, `go_notice`) VALUES ('100万钻石公益计划', '100万钻石公益计划', '{\"title\":\"活动说明\",\"content\":[{\"title\":\"活动时间\",\"desc\":\"8月31日结束\"},{\"title\":\"福利获得\",\"desc\":\"活动期间内圈内粉丝累计使用钻石数量达到解锁目标，即可获得福利。\"},{\"title\":\"福利领取\",\"desc\":\"以上奖励不限名次，不限排名，达到即可解锁获得。\"},{\"title\":\"福利说明\",\"desc\":\"目标福利为累计福利，一个爱豆总共可获得以爱豆名义捐赠公益植树、爱心书箱、爱心午餐和小程序开屏3天。\"},{\"title\":\"其他说明\",\"desc\":\"小程序开屏所选日期为：11月01日-11月30日。\"},{\"title\":\"如何领取\",\"desc\":\"达到目标联系客服选择日期和领取公益。\"}]}', 1598803200, '{\"progress\":[{\"step\":1000000,\"reward_desc\":[\"小程序开屏1天\",\"公益植树x10\"]},{\"step\":3000000,\"reward_desc\":[\"小程序开屏1天\",\"爱心书箱x1\"]},{\"step\":6000000,\"reward_desc\":[\"小程序开屏1天\",\"爱心午餐x50\"]}],\"banner\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9HKFm38Dv98TP2KyPl7wR9Q95DeFWWvAmQ4N449lgZs6ficYqiaUbqoDmzHsVHlNujjammIicUq0qVSA/0\",\"share_id\":110}', '2020-07-31 15:48:05', '2020-07-31 15:48:05', NULL, 'STONE_WELFARE', NULL);

INSERT INTO `f_cfg_scrap`(`name`, `key`, `desc`, `count`, `limit_exchange`, `exchange_number`, `type`, `status`, `image_s`, `image_l`, `create_time`, `update_time`, `delete_time`, `extra`) VALUES ('10月25日小程序开屏', 'open_1025', '10月25日小程序开屏', 10, 1, 0, 'WEEK', 'ON', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GqEna3Bu4hOUqY2ruicPUKoOxUXk3YG5KlOA4lYByS7xppcrEwOcJAQeUia6IxKBRicmwBtzgfELQYg/0', '2020-07-30 17:23:33', '2020-07-30 17:23:33', NULL, NULL);
INSERT INTO `f_cfg_scrap`(`name`, `key`, `desc`, `count`, `limit_exchange`, `exchange_number`, `type`, `status`, `image_s`, `image_l`, `create_time`, `update_time`, `delete_time`, `extra`) VALUES ('10月26日小程序开屏', 'open_1026', '10月26日小程序开屏', 10, 1, 0, 'WEEK', 'ON', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GqEna3Bu4hOUqY2ruicPUKoOxUXk3YG5KlOA4lYByS7xppcrEwOcJAQeUia6IxKBRicmwBtzgfELQYg/0', '2020-07-30 17:23:33', '2020-07-30 17:23:33', NULL, NULL);
INSERT INTO `f_cfg_scrap`(`name`, `key`, `desc`, `count`, `limit_exchange`, `exchange_number`, `type`, `status`, `image_s`, `image_l`, `create_time`, `update_time`, `delete_time`, `extra`) VALUES ('10月27日小程序开屏', 'open_1027', '10月27日小程序开屏', 10, 1, 0, 'WEEK', 'ON', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GqEna3Bu4hOUqY2ruicPUKoOxUXk3YG5KlOA4lYByS7xppcrEwOcJAQeUia6IxKBRicmwBtzgfELQYg/0', '2020-07-30 17:23:33', '2020-07-30 17:23:33', NULL, NULL);
INSERT INTO `f_cfg_scrap`(`name`, `key`, `desc`, `count`, `limit_exchange`, `exchange_number`, `type`, `status`, `image_s`, `image_l`, `create_time`, `update_time`, `delete_time`, `extra`) VALUES ('金豆X100万', 'coin', '金豆X100万', 1, 0, 0, 'WEEK', 'ON', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5uWEQk2MTaRfxdveGhfFhS1G5dUIkwlT5fosfMaW0c9aQKy3mH3XAew/0', '2020-08-04 16:49:43', '2020-08-04 16:49:43', NULL, '{\"number\":1000000,\"person\":30}');

INSERT INTO `f_prop`(`title`, `name`, `img`, `point`, `desc`, `remain`, `create_time`, `update_time`, `delete_time`, `get_type`, `key`) VALUES ('幸运抽奖券', '幸运抽奖券', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GqEna3Bu4hOUqY2ruicPUKo5M09v5iajLMIlAb5MR4ib0kA9OnkhXodC6M6SmjAjmjj7VcwgUYklmfA/0', 0, '使用抽奖券，可获得大量金豆、鲜花、钻石', -1, '2020-07-29 11:34:32', '2020-08-04 11:20:30', NULL, 'CHARGE', 'lucky_draw');
--

--  end V3

-- 上次已过期的碎片数
ALTER TABLE `f_user_ext`
ADD COLUMN `last_scrap` bigint(20) NULL COMMENT '上次已过期的碎片数' AFTER `scrap`;
ALTER TABLE `f_user_ext`
ADD COLUMN `scrap_time` timestamp(0) NULL DEFAULT null COMMENT '上次更新过期时间' AFTER `last_scrap`;

-- 新增type区分多次抽奖和单次抽奖
alter table f_rec_lucky_draw_log
	add type enum('SINGLE', 'MULTIPLE', 'EXCHANGE') default 'SINGLE' not null;

create index f_rec_lucky_draw_log_type_index
	on f_rec_lucky_draw_log (type);

-- 新增用户占领记录表
-- V7
drop table if exists f_user_achievement_heal;
create table f_user_achievement_heal
(
    id           int auto_increment,
    user_id      int                                                                      not null,
    star_id      int                                                                      not null,
    sum_time     bigint                                         default 0                 not null comment '累计时间',
    count_time   int                                            default 0                 not null comment '计数时间',
    type         enum ('flower_time', 'flower', 'pk', 'newguy') default 'flower_time'     not null comment 'flower_time 宣传官
flower 花神
pk 守护神
newguy 明日之星',
    invite_count int                                            default 0                 null,
    invite_sum   int                                            default 0                 not null,
    invite_day   int                                            default 0                 not null,
    invite_time  int                                                                      null,
    create_time  timestamp                                      default CURRENT_TIMESTAMP not null,
    update_time  timestamp                                      default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time  timestamp                                                                null,
    constraint f_user_occupy_id_uindex
        unique (id)
)
    comment '成就挂饰表';

create index f_user_occupy_s_index
    on f_user_achievement_heal (star_id);

create index f_user_occupy_u_index
    on f_user_achievement_heal (user_id);

alter table f_user_achievement_heal
    add primary key (id);


alter table f_user_star
	add day_flower bigint default 0 not null comment '每日贡献鲜花值';

alter table f_user_star
	add yesterday_flower bigint default 0 not null comment '昨日贡献鲜花值';

alter table f_user_star
	add achievement_flower bigint default 0 not null comment '成就挂饰鲜花总贡献';

alter table f_user_star
	add achievement_count bigint default 0 not null comment '成就挂饰总贡献';

alter table f_user_star change achievement_count achievement_week_count bigint default 0 not null comment '成就挂饰周贡献新数据';

alter table f_user_star
	add achievement_month_count bigint default 0 not null comment '成就挂饰月贡献新数据';

alter table f_pk_user_rank
	add achievement_total_count bigint default 0 not null comment '成就挂饰总贡献新数据';

alter table f_cfg_headwear
	add `key` varchar(156) null;

alter table f_cfg_headwear
	add type enum('NORMAL', 'ACHIEVEMENT') default 'NORMAL' not null;

INSERT INTO `f_cfg_taskgift_category`(`name`, `banner`, `start_time`, `end_time`, `create_time`, `update_time`, `delete_time`) VALUES ('成就头饰', NULL, NULL, NULL, '2020-08-11 14:40:11', '2020-08-12 10:33:29', NULL);

INSERT INTO `f_cfg_taskgift`(`title`, `awards`, `category_id`, `count`, `create_time`, `update_time`, `delete_time`) VALUES ('花神', '{\"achievement\":\"flower\",\"title\":\"花神\",\"desc\":\"鲜花日榜1-10名\",\"modal\":\"send\"}', 5, 3, '2020-08-11 14:58:13', '2020-08-12 14:59:19', NULL);
INSERT INTO `f_cfg_taskgift`(`title`, `awards`, `category_id`, `count`, `create_time`, `update_time`, `delete_time`) VALUES ('守护神', '{\"achievement\":\"pk\",\"title\":\"守护神\",\"desc\":\"团战贡献1-3名\",\"gopage\":\"/pages/pk/pk_index\"}', 5, 3, NULL, '2020-08-12 14:59:19', NULL);
INSERT INTO `f_cfg_taskgift`(`title`, `awards`, `category_id`, `count`, `create_time`, `update_time`, `delete_time`) VALUES ('宣传官', '{\"achievement\":\"flower_time\",\"title\":\"宣传官\",\"desc\":\"累计邀请100名新人\",\"modal\":\"invit_desert\"}', 5, 3, '2020-08-11 14:58:13', '2020-08-12 14:59:19', NULL);
INSERT INTO `f_cfg_taskgift`(`title`, `awards`, `category_id`, `count`, `create_time`, `update_time`, `delete_time`) VALUES ('明日之星', '{\"achievement\":\"newguy\",\"title\":\"明日之星\",\"desc\":\"注册日贡献排名1-10名\",\"modal\":\"send\"}', 5, 3, NULL, '2020-08-12 14:59:52', NULL);

INSERT INTO `f_cfg_headwear`(`img`, `diamond`, `key`, `type`, `create_time`, `update_time`, `delete_time`, `sort`, `days`) VALUES ('https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GOYEnDnecuk4yqY2h5wj7Rx2nGlTYia5yBqnIic0BTHdj76b938EnkBFs7ydft1DTerBaUTRBPN2FQ/0', 0, 'achievement_flower_time', 'ACHIEVEMENT', '2020-08-11 10:07:09', '2020-08-12 18:09:03', NULL, 0, 3);
INSERT INTO `f_cfg_headwear`(`img`, `diamond`, `key`, `type`, `create_time`, `update_time`, `delete_time`, `sort`, `days`) VALUES ('https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GOYEnDnecuk4yqY2h5wj7RGBSM0YT9wbsicOPyYGc7X8BrLUOXIUwtgxySrzDFhthovBZbL9QFPug/0', 0, 'achievement_flower', 'ACHIEVEMENT', '2020-08-11 16:24:31', '2020-08-12 18:09:03', NULL, 0, 3);
INSERT INTO `f_cfg_headwear`(`img`, `diamond`, `key`, `type`, `create_time`, `update_time`, `delete_time`, `sort`, `days`) VALUES ('https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GOYEnDnecuk4yqY2h5wj7R4Wcn537xB79ibXz7ia3Ixjy9QeibvHgKX6ibVqhbVd7bgNuIWMMLCmU8tg/0', 0, 'achievement_new_guy', 'ACHIEVEMENT', '2020-08-11 16:24:31', '2020-08-12 18:09:03', NULL, 0, 3);
INSERT INTO `f_cfg_headwear`(`img`, `diamond`, `key`, `type`, `create_time`, `update_time`, `delete_time`, `sort`, `days`) VALUES ('https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GOYEnDnecuk4yqY2h5wj7RicdbcnxAK9CYpzqAxxeadJnia1UMPwiaPibVnDGBoxRrpGEcSsbjVTZGtw/0', 0, 'achievement_pk', 'ACHIEVEMENT', '2020-08-11 16:24:31', '2020-08-12 18:09:03', NULL, 0, 3);

INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('成就挂饰', 'achievement', '{\"top_header\":{\"title\":\"成就挂饰\",\"tip\":{\"label\":\"说明\",\"gopage\":\"/pages/notice/notice?id=1\"}},\"banner\":[{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EOWV82IkeqFRibMgcWRnrqIxoAldG2wfuiaiaRs6LBVvibibxeT6xJRVSCw7r1fy5jIjT3VvYo3pjCpFg/0\",\"gopage\":\"\"},{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EOWV82IkeqFRibMgcWRnrqIQRJ16AEMgOAVEybwaicQ3aO5icezd5bLnkxQHlAmQ0JiaqzhhqIdQL32A/0\",\"gopage\":\"\"},{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EOWV82IkeqFRibMgcWRnrqIqjnmLT6vYIB2qQqGNy8QIz7J9TM9t5XVSY055VQQm9ia8kQDUWCdTPg/0\",\"gopage\":\"\"},{\"img_url\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EOWV82IkeqFRibMgcWRnrqIgpgYpqYns2eda7qvqiaTak8JZOzURT6t6seaMe6b7tr4zrXmqGoAchw/0\",\"gopage\":\"\"}],\"rank_group\":[{\"label\":\"花神\",\"value\":\"flower\",\"btn\":[{\"label\":\"今日实时\",\"value\":\"today\"},{\"label\":\"昨日排名\",\"value\":\"yesterday\"},{\"label\":\"圈内排名\",\"value\":\"star\"},{\"label\":\"总排名\",\"value\":\"all\"}]},{\"label\":\"守护者\",\"value\":\"pk\",\"btn\":[{\"label\":\"今日实时\",\"value\":\"today\"},{\"label\":\"圈内排名\",\"value\":\"star\"},{\"label\":\"总排名\",\"value\":\"all\"}]},{\"label\":\"明日之星\",\"value\":\"newguy\",\"btn\":[{\"label\":\"今日实时\",\"value\":\"today\"},{\"label\":\"昨日\",\"value\":\"yesterday\"},{\"label\":\"周榜\",\"value\":\"week\"},{\"label\":\"月榜\",\"value\":\"month\"}]},{\"label\":\"宣传官\",\"value\":\"flower_time\",\"btn\":[{\"label\":\"今日实时\",\"value\":\"today\"},{\"label\":\"圈内排名\",\"value\":\"star\"},{\"label\":\"总排名\",\"value\":\"all\"}]}]}', 1, '2020-08-08 14:17:15', '2020-08-14 14:20:47', NULL);
-- end

-- 拉新活动
drop table if exists f_user_invite;
create table f_user_invite
(
    id                int auto_increment,
    user_id           int                                 not null,
    star_id           int                                 not null,
    invite_day        int       default 0                 not null,
    invite_sum        int       default 0                 not null,
    invite_day_settle text                                null,
    create_time       timestamp default CURRENT_TIMESTAMP not null,
    update_time       timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time       timestamp                           null,
    constraint f_user_invite_id_uindex
        unique (id)
)
    comment '用户拉新表';

create index f_user_invite_s_index
    on f_user_invite (star_id);

create index f_user_invite_u_index
    on f_user_invite (user_id);

create index f_user_invite_us_index
    on f_user_invite (user_id, star_id);

alter table f_user_invite
    add primary key (id);

drop table if exists f_rec_user_invite;
create table f_rec_user_invite
(
    id          int auto_increment,
    user_id     int       default 0                 null,
    star_id     int       default 0                 not null,
    title       varchar(255)                        not null,
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time timestamp                           null,
    reward      text                                null,
    constraint f_rec_user_invite_id_uindex
        unique (id)
)
    comment '用户领奖记录表';

create index f_rec_user_invite_star_index
    on f_rec_user_invite (star_id);

create index f_rec_user_invite_u_index
    on f_rec_user_invite (user_id);

create index f_rec_user_invite_us_index
    on f_rec_user_invite (user_id, star_id);

alter table f_rec_user_invite
    add primary key (id);

alter table f_star
	add invite_sum bigint default 0 not null comment '累计拉新';

alter table f_star
	add invite_count int default 0 not null comment '拉新奖励计算';

alter table  f_user_star_bakup
	add day_flower bigint default 0 not null comment '每日贡献鲜花值';

alter table  f_user_star_bakup
	add yesterday_flower bigint default 0 not null comment '昨日贡献鲜花值';

alter table  f_user_star_bakup
	add achievement_flower bigint default 0 not null comment '成就挂饰鲜花总贡献';

alter table  f_user_star_bakup
	add achievement_count bigint default 0 not null comment '成就挂饰总贡献';

alter table  f_user_star_bakup change achievement_count achievement_week_count bigint default 0 not null comment '成就挂饰周贡献新数据';

alter table  f_user_star_bakup
	add achievement_month_count bigint default 0 not null comment '成就挂饰月贡献新数据';
INSERT INTO `f_cfg_share`(`desc`, `title`, `imageUrl`, `path`, `create_time`, `update_time`, `delete_time`) VALUES ('拉新助力', '为STARNAME赢得11月开屏，快来助我一臂之力', NULL, 'path=/pages/group/group', '2020-08-14 10:59:49', '2020-08-14 15:36:51', NULL);
INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('拉新助力解锁', 'invite_assist', '{\"banner\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Fc6k4icARtLOpP2kdnMiaic0oGemvqN43zUUia0ftswfBZ0Ch4kjiblryV4BK4Pb8DJBiagibyUWlGtD4BA/0\",\"time\":{\"start\":\"2020-08-09 00:00:00\",\"end\":\"2020-09-30 23:59:59\"},\"share_id\":111,\"idol_tip\":\"邀请新用户助力拉新解锁\",\"my_title\":\"个人拉新奖励\",\"go_notice\":\"/pages/notice/notice?id=1\",\"idol_reward\":{\"state\":100,\"reward\":{\"week_hot\":1000000,\"type\":\"hot\",\"number\":1000000}},\"idol_progress\":[{\"value\":1000,\"reward\":[\"小程序开屏一天\",\"200应援金\"]},{\"value\":3000,\"reward\":[\"小程序开屏一天\",\"300应援金\"]},{\"value\":6000,\"reward\":[\"小程序开屏一天\",\"500应援金\"]}],\"my_progress\":[{\"value\":3,\"reward\":{\"name\":\"金豆*1万\",\"number\":10000,\"key\":\"coin\",\"type\":\"currency\"}},{\"value\":5,\"reward\":{\"name\":\"金豆福袋\",\"number\":1,\"end_time\":604800,\"key\":10,\"type\":\"prop\",\"img\":\"https://mmbiz.qpic.cn/mmbiz_png/h9gCibVJa7JXX6zqzjkSn01fIlGmzJw6ufzJPQqnQz9PQwhL9d2jCL8x6qlic5VDiaWU3XkPSZfZ4ZRVau9DQVtKg/0\"}},{\"value\":10,\"reward\":{\"name\":\"钻石*10\",\"number\":10,\"key\":\"stone\",\"type\":\"currency\"}},{\"value\":15,\"reward\":{\"name\":\"鲜花*2万\",\"number\":20000,\"key\":\"flower\",\"type\":\"currency\"}}],\"settle_title\":\"领取记录\",\"count_title\":\"贡献榜\"}', 1, '2020-08-13 14:28:55', '2020-08-14 14:44:21', NULL);
-- end

-- 新增碎片成品排序
alter table f_cfg_scrap
	add sort int default 0 not null comment '排序 0-99整数 越大越靠前';

-- 记录用户上次抽奖时间以便限制用户抽奖间隙
alter table f_user_ext
    add lottery_star_time int default 0 not null comment '上次抽奖时间戳';

-- 把抽奖携程配置以便以后改动
INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('免费抽奖配置', 'free_lottery', '{\"enable_double\":true,\"notice\":{\"title\":\"抽奖规则\",\"desc\":[\"1、每人每天抽奖机会上限为 100次\",\"2、在线时，每分钟恢复一次抽奖机会，存储上限为30次\",\"3、离线时，每分钟恢复一次抽奖机会，存储上限为10次\",\"4、周五、周六、周日奖励翻倍\",\"5、抽奖次数0点清零\"]},\"enable_video_add\":{\"status\":false,\"number\":5,\"text\":\"立即获得5次\"},\"day_max\":100,\"start_limit_time\":0,\"auto_add_time\":60,\"add_max\":30,\"first_max\":10}', 1, '2020-08-21 10:46:14', '2020-08-21 13:37:35', NULL);

-- 新增支付方式
alter table f_rec_pay_order
	add pay_type varchar(150) default 'wechat_pay' not null;

create index f_rec_pay_order_payty_index
	on f_rec_pay_order (pay_type);

-- 宝箱分享修改
UPDATE `f_cfg_share` SET `path` = 'path=/pages/lottery/box_open' WHERE `id` = 8;

drop table if exists f_cfg_animal;
create table f_cfg_animal
(
    id          int auto_increment,
    name        varchar(255)                           not null,
    image       varchar(255)                           not null,
    type        varchar(150) default 'NORMAL'          not null,
    `lock`      int          default 0                 not null,
    lock_num    int          default 0                 not null,
    create_time timestamp    default CURRENT_TIMESTAMP null,
    update_time timestamp    default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time timestamp                              null,
    exchange int not null comment '碎片ID用于解锁｜升级',
    scrap_name        varchar(255)                        not null,
    scrap_img varchar(255) not null,
    star_id     int                                    null,
    constraint f_cfg_pet_id_uindex
        unique (id)
)
    comment '宠物配置表';

create index f_cfg_pet_type_index
    on f_cfg_animal (type);

alter table f_cfg_animal
    add primary key (id);

drop table if exists f_cfg_animal_level;
create table f_cfg_animal_level
(
    id          int auto_increment,
    animal_id   int                                 not null,
    level       int       default 1                 not null,
    number      int       default 0                 not null comment '碎片解锁数量',
    `desc`      varchar(255)                        null,
    output      int       default 0                 not null comment '每10秒产豆数',
    steal       int       default 0                 not null comment '偷豆数',
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp          default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time timestamp                           null,
    constraint f_cfg_animal_level_id_uindex
        unique (id)
)
    comment '宠物等级';

create index f_cfg_animal_level_lv_index
    on f_cfg_animal_level (level);

alter table f_cfg_animal_level
    add primary key (id);

drop table if exists f_animal_lottery;
create table f_animal_lottery
(
    id          int auto_increment,
    animal   int                                   not null,
    chance      float(5, 2) default 0.00              not null,
    number      int         default 1                 not null,
    create_time timestamp   default CURRENT_TIMESTAMP not null,
    update_time timestamp   default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time timestamp                             null,
    constraint f_animal_draw_id_uindex
        unique (id)
)
    comment '宠物碎片抽奖池';

alter table f_animal_lottery
    add primary key (id);

drop table if exists f_user_animal;
create table f_user_animal
(
    id          int auto_increment,
    user_id     int                                 not null,
    animal_id   int                                 not null,
    scrap       int       default 0                 not null comment '碎片数',
    level       int       default 0                 not null comment '等级
默认0-未解锁',
    `lock`      int       default 0                 not null comment '是否锁定
0-已解锁
[int]-需要解锁的数量',
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp          default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time timestamp                           null,
    constraint f_user_animal_id_uindex
        unique (id)
)
    comment '用户宠物表';

create index f_user_animal_an_index
    on f_user_animal (animal_id);

create index f_user_animal_u_index
    on f_user_animal (user_id);

create index f_user_animal_ua_index
    on f_user_animal (user_id, animal_id);

alter table f_user_animal
    add primary key (id);

drop table if exists f_user_manor;
create table f_user_manor
(
    id                int auto_increment,
    user_id           int                                 not null,
    day_count         int       default 0                 not null comment '今日金豆产量',
    count_left        int       default 0                 not null comment '剩余产量',
    background        varchar(255)                        null,
    week_count        bigint    default 0                 not null comment '本周产量',
    sum               bigint    default 0                 not null comment '庄园总产量',
    create_time       timestamp default CURRENT_TIMESTAMP not null,
    update_time       timestamp default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time       timestamp                           null,
    last_output_time  int       default 0                 not null comment '上次产豆时间',
    day_steal         int       default 0                 not null comment '每日偷豆数量',
    day_lottery_times int       default 0                 not null,
    use_animal        int       default 1                 not null,
    get_flower_reward int       default 0                 not null comment '是否领取限时上线鲜花福利',
    constraint f_user_manor_id_uindex
        unique (id),
    constraint f_user_manor_user_index
        unique (user_id)
)
    comment '用户庄园';

alter table f_user_manor
    add primary key (id);

drop table if exists f_cfg_manor_background;
create table f_cfg_manor_background
(
    id          int auto_increment,
    url         varchar(255)                                 not null,
    name        varchar(255)                                 not null,
    status      enum ('ON', 'OFF') default 'OFF'             not null,
    url_s       varchar(255)                                 not null,
    create_time timestamp          default CURRENT_TIMESTAMP not null,
    update_time timestamp          default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP,
    delete_time timestamp                                    null,
    constraint f_cfg_manor_background_id_uindex
        unique (id)
)
    comment '庄园背景';

alter table f_cfg_manor_background
    add primary key (id);

drop table if exists f_manor_steal_log;
create table f_manor_steal_log
(
    id          int auto_increment,
    user_id     int                                 not null comment '小偷',
    steal_id    int                                 not null comment '受害者',
    number      int       default 0                 not null comment '偷取数量',
    create_time timestamp default CURRENT_TIMESTAMP not null,
    update_time timestamp default CURRENT_TIMESTAMP not null,
    delete_time timestamp                           null,
    constraint f_manor_steal_log_id_uindex
        unique (id)
)
    comment '偷取日志';

create index f_manor_steal_log_steal_index
    on f_manor_steal_log (steal_id);

create index f_manor_steal_log_us_index
    on f_manor_steal_log (user_id, steal_id);

create index f_manor_steal_log_user_index
    on f_manor_steal_log (user_id);

alter table f_manor_steal_log
    add primary key (id);

alter table f_user_ext
	add animal_lottery int default 0 not null comment '每日召唤宠物次数';

alter table f_user_currency
	add panacea int(11) unsigned default 0 not null comment '灵丹';

alter table f_rec
	add panacea bigint default 0 not null;

alter table f_rec
	add before_panacea bigint default 0 not null;

create table f_rec_panacea_task
(
    id              int auto_increment
        primary key,
    task_id         int                                 null,
    user_id         int                                 null,
    done_times      bigint    default 0                 null comment '已完成次数',
    is_settle_times int       default 0                 null comment '已领奖励次数',
    create_time     timestamp default CURRENT_TIMESTAMP null,
    update_time     timestamp default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    delete_time     timestamp                           null,
    constraint task_id
        unique (task_id, user_id)
)
    comment '记录-任务
task_type = 1为每日任务
师徒';

create table f_cfg_panacea_task
(
    id          int auto_increment
        primary key,
    name        varchar(255)                                                  null,
    icon        varchar(255)                                                  null,
    `desc`      varchar(255)                                                  null comment '描述',
    sort        int                                 default 0                 null comment '排序',
    done        bigint                              default 0                 not null comment '完成任务所需要的数值',
    limit_times int                                 default 0                 not null comment '完成任务的每天的上限',
    `key`       varchar(255)                                                  null comment '任务key',
    reward      int                                 default 0                 not null comment '奖励',
    type        enum ('SUM', 'DAY', 'ONCE', 'RANK') default 'SUM'             not null comment '累计任务
每日任务
单次任务',
    btn_text    varchar(255)                        default ''                null comment '未完成时的按钮文字',
    gopage      varchar(255)                                                  null comment '未完成时的跳转页面',
    open_type   varchar(255)                                                  null comment '调用button组件的open-type',
    shareid     int(11) unsigned                    default 0                 null comment 'cfg_share表中的id',
    create_time timestamp                           default CURRENT_TIMESTAMP null,
    update_time timestamp                           default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    delete_time timestamp                                                     null,
    status      enum ('ON', 'OFF')                  default 'ON'              not null,
    extra       varchar(255)                                                  null
)
    comment '任务-获取灵丹';

INSERT INTO `f_cfg_panacea_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`, `extra`) VALUES ('贡献人气', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9G3d5wQSx6E5w4aNVVRmUp2KdEBGSKOMnicH9DAMlajZszybNCdEicTtBfLbgXbbrX7nhibKPg5GWXoQ/0', '每次贡献', 1, 1000000, 0, 'SUM_COUNT', 1, 'SUM', '去打榜', '/pages/group/group', NULL, 0, '2020-06-11 21:44:19', '2020-09-01 15:51:24', NULL, 'ON', NULL);
INSERT INTO `f_cfg_panacea_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`, `extra`) VALUES ('使用鲜花', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0', '每次冲榜使用鲜花', 2, 1000000, 0, 'USE_FOLLOWER', 3, 'SUM', '去打榜', '/pages/group/group', NULL, 0, '2020-07-20 09:30:02', '2020-09-01 15:56:51', NULL, 'ON', NULL);
INSERT INTO `f_cfg_panacea_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`, `extra`) VALUES ('补充鲜花', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FquzibbuL4TMQyMbRKaQyUhLBDFLSfEgnRuQZBDwBQHBqr9Y4tMicSDcDjzwWhC4Xh0z19SmBaX2rg/0', '每次补充鲜花', 6, 35, 0, 'RECHARGE', 5, 'SUM', '回复“1”', '/pages/charge/charge', 'contact', 0, '2020-07-20 09:37:16', '2020-09-01 15:56:47', NULL, 'ON', NULL);
INSERT INTO `f_cfg_panacea_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`, `extra`) VALUES ('鲜花日榜', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0', '每日鲜花日榜top10', 12, 0, 0, 'FLOWER_RANK', 0, 'RANK', ' 系统发放', '/pages/achievement_rank/achievement_rank?type=0', NULL, 0, '2020-09-01 16:22:36', '2020-09-01 19:04:24', NULL, 'ON', '[5,6,8,11,15,20,25,30,40,50]');
INSERT INTO `f_cfg_panacea_task`(`name`, `icon`, `desc`, `sort`, `done`, `limit_times`, `key`, `reward`, `type`, `btn_text`, `gopage`, `open_type`, `shareid`, `create_time`, `update_time`, `delete_time`, `status`, `extra`) VALUES ('PK粉丝榜', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxriczDwYpJxLicMALveZ8I6vxIGDDu9yB41Dicq9XYTtUcggaFYvQEc2ng/0', 'PK粉丝榜top10', 13, 0, 0, 'PK_RANK', 0, 'RANK', ' 系统发放', '/pages/achievement_rank/achievement_rank?type=1', NULL, 0, '2020-09-01 16:22:36', '2020-09-01 19:08:57', NULL, 'ON', '[1,2,3,4,5,6,8,11,15,20]');

INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('鼠', 'https://mmbiz.qpic.cn/mmbiz_gif/w5pLFvdua9Fic6VmPQYib2ktqATmSxJmUtvNXVsBzTEmc1fyK8O16OSuJUAicicLZA0o1hkNVmBoSqKZUj89srXPvA/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6QhT8Zws3rWRdHxribSNudmOUjMjv17TxfCTLhDwKKRCaW0VwbNRzUlQA/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6QOpdWkhyqdYQ2icwwiborbFn9uXEDnyI3FsHiaHia5UwOPjFYibjVO0htb8g/0', 0, 0, '2020-08-31 10:23:04', '2020-09-01 16:42:20', NULL, 0, '鼠碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('蛇', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6QVmJQ6JehzPGiat0XdOVwmricy7jp3bHmhUzEZD3dq0hcLv0pbxhu07vg/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6Qe6fJs4QeSialZSOl6dJOxu1TfNJDPz9RmBaoZTtru5gW4otTvIYDyJg/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6QECzKufPLB0iabwpTexhzjkNA3MMeR0paTD2zu0M5liamWRGMVImia1krA/0', 0, 0, '2020-08-31 10:23:04', '2020-08-31 16:53:46', NULL, 0, '蛇碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('牛', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6QXicfxIKJH5t2Vua5konlmicGfghKG5CRrsNsic6kXAc3PDn45kHQfbFCw/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6QNSsctQX9LTd3qIcp9OMG5cxANib4SBDt1ReqibBYqQ49uEDw8ibB0WA2w/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GF0Ayowf19yN8oiaLKldV6QP4yk4QSA5XEeK3fmf5ia29IhK3UYH9zDffgodp8vBqiaq8GRZJKwfGdQ/0', 0, 0, '2020-08-31 10:23:04', '2020-08-31 16:55:15', NULL, 0, '牛碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('小凯', '', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', NULL, 0, 0, '2020-09-02 10:35:48', '2020-09-02 15:04:51', '2020-09-02 14:14:20', 0, '幸运碎片', 1);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('龙', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9n0vEW0h4WJYwYGGe1dWOwKhCBs6PlwPmMAtlFcLGMWoLKF5HH2bakw/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9M3U2ahMGLwyQE59IIcuxk8pMGRy7JSc5ib0ib8UpzcWvnV3K4PGibYqBg/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV92XodSNVDKpVqktY7ggvBgOoCC58mRtfiaGr8p3ue7Xh9btH0BibyQd1A/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:16:47', NULL, 0, '龙碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('猪', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9weyVWAQEvq5gvjb0iaSxleiaK1cGKusSic5ygQFDHrPNiagibKeOGktQntQ/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9JvQ88GpLa0VTGZnialw0ibBQmRFLf39ibBeiaBd0exnERMNYOfhTS6sxPA/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9HxMC8UcibnpiakGBkbvNWNVfJpuI8Kg8Q3osfUl4Kko67NayibOH69icTg/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:19:46', NULL, 0, '猪碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('猴', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9XoIzLC6tVR3lpJvEwADRFiaqVQKoETicqwPhMP4ADMNxBXibmArZRIIaw/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV99Fh18MnEcSZLay9ge47WlLpH4ytP5Qt8dQPeaND8Jib9RP1cXwxmeibA/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9sexpnSR9dxkNRL8Q4DOWbpicJ3GOd9oJa5Q4R4jQickH0HKN8djVc0xg/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:09:44', NULL, 0, '猴碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('马', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9EqEugOxWNmShdiaPovb8ypolYvrk597p1GKw9uoEBwLp9UDHf10LENQ/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9YRibfDnrMx74yJvrtibzXiboJiarN0CMsc5wR0dFfdzpLUSOLH56ghk7Ug/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9noV2RDgvNvaevL2puxpJDrDkWwiaNpnPhicX6k7aCasMeoGlwWlyFAHw/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:21:20', NULL, 0, '马碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('虎', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9zniaMQRL5tVicGLYuG2rNTWsYVERnQtpydibuz3ftmn0SlH3FOdAeVloQ/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9ITFtMXia5eA7pkLSeRLHPibiaWBkEy7Y5mL6dYhL9FgMsLN8n7Pc9DqLA/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV91tKnCv0ibLKY5spRr8LJWzwvibLGdumov4wfWThHyyEVkbIjUIDo3icSg/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:23:12', NULL, 0, '虎碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('兔', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9pa3q5zs8g16B9AyCA2Jocd7CNf5XSNP9Ncnw8tJr9dib6L5YAhDdRrw/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9EDL7kK4oy1olwibFVq5iacH9Wj1OdWn4BQ09p2DWpianknADrxJXpJ4mA/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9A2BicAmBoBLlK5FZEkBbYBnLzRUibwIMq4jt3vxEGfIGyTuOgjlHDMiag/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:24:32', NULL, 0, '兔碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('鸡', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV90MabSTblNRNgbDU6PXYd45hsMFrRKlZRWQTiaxmS9IuiaKy6SMliaQicCw/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9wVH2Z12VdTs8NPNgMlVbUbnS4C7AH9P6XALCsqhbsSVcGTG9BRxoDw/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9MEYpyrPyIr1QAd8fJWwvlj2DNPZo4dDN7akZzoIkr4JPPiaoYWQ0pEg/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:25:46', NULL, 0, '鸡碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('羊', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9QfjwgyRVicMr05vI0OmibxpJ8XQyI89phlfG8EGDB4mupYNZZ8vZhAsg/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV967tNOHibS4sHdTzdGdPlb4t7Vnm95RWhhuqWrkvvbbpCPxLWKUEibZLw/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9icFW0MTg7rjsnd7Mcbias3lVkauceS4RZR2sAmI6sICPxd4FxSLIgcHA/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:18:14', NULL, 0, '羊碎片', NULL);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('夜神', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9FDow0vAJ49hY7vW9bBWE6prNqHrTmPHwWxunpw0Gys4hZVpAWvc1cw/0', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9FoYpz5psFVynafiaticsmWdme4uMlytzUGv61eFMghsVnwYhIy9SAyYw/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:06:15', NULL, 0, '幸运碎片', 1617);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('农农', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9xCHnvSBybx6GKgRe8JRVWh8d1VG4gzuaPzN1KXvZUxH8Lvgh5HIWQw/0', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9xCHnvSBybx6GKgRe8JRVWh8d1VG4gzuaPzN1KXvZUxH8Lvgh5HIWQw/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:07:07', NULL, 0, '幸运碎片', 529);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('火神', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9pN0yK45icH5O6IssgWDGUarUOyeicGclN89gyR9lob7wHXiaKxCqIaQ2g/0', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV946nmicentK4xyfia6Q3gVa3CRhbxCaSrUNkVwEzhBajib9gDxvr4Dg7YQ/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:06:15', NULL, 0, '幸运碎片', 37);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('阿羡', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV97HuCDfUVvLkmYicruLqhvmp3EokUOSIWnkWSsrtUcs0vqqqCBrrrFBQ/0', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9TzJ2N7DSxwusfHNicQCRQlUtly3IIcfYTbHfSicb09QbODOic5gO2p8yQ/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:06:15', NULL, 0, '幸运碎片', 538);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('二爷', '', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', NULL, 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:04:31', '2020-09-02 14:14:20', 0, '幸运碎片', 2532);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('含光君', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9D7IickGPfD70ia2OZFxWLx5QzGtqY6eZaGxuqWFQzZAZbLPwmibBocxjA/0', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9F7HFWEvj0xJqrbINDB4RDB0jllgicONY6JuM6PfFzp5qUIOwk8jxC7w/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:06:16', NULL, 0, '幸运碎片', 593);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('小川', '', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', NULL, 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:04:04', '2020-09-02 14:14:20', 0, '幸运碎片', 960);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('小演', '', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', NULL, 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:04:04', '2020-09-02 14:14:20', 0, '幸运碎片', 106);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('小巍', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9C0UVyHCnu2vCncia378ovibTbQwdicclFG5ymeiccyVMT1w2dNyk5gQUeg/0', 'SECRET', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GXvpB3e5ibvGiadFqIOl7vceee3ribmebyLp4YUkEa7my8VjaX641mQdlnTgrXCl0xWLSIicQMKicKb3Q/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9ONsBznOkXl54yrn1CnSeuGhar3brTsZCwcLAjWBSoyoVlkbd0Uq2lQ/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 15:23:52', NULL, 0, '幸运碎片', 9);
INSERT INTO `f_cfg_animal`(`name`, `image`, `type`, `scrap_img`, `empty_img`, `lock`, `lock_num`, `create_time`, `update_time`, `delete_time`, `exchange`, `scrap_name`, `star_id`) VALUES ('狗', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9p46kiaoIRPhtMDLS0fVlDeLZ83F2iay2qGIlFvM1TNvTMhFqs3ooykLA/0', 'NORMAL', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9hNiagmTlkjXGYpjXgkc1BbibLGgNsshVS7A1DEib1FlKFECsPhCEBjOhQ/0', 'https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FRl348Pm93ersaH77htmV9wn0jdnjiarQeKYZlmdpFOE4TesxJC1EyXAibJ3Re9p05YrlRbb8NHHeg/0', 0, 0, '2020-09-02 13:55:21', '2020-09-02 14:09:44', NULL, 0, '狗碎片', NULL);

alter table f_user_manor
	add output bigint default 0 not null comment '庄园产量 每10秒/X金豆';


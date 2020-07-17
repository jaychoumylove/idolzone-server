
-- start 2020-07-10 17:22:37
-- 新建狗4数据库以及记录

-- ----------------------------
-- Table structure for f_cfg_pet_skill_four
-- ----------------------------
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

-- ----------------------------
-- Records of f_cfg_pet_skill_four
-- ----------------------------
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

-- end

-- start 用户精灵新增4技能字段 2020-07-10 17:32:47
alter table f_user_sprite
	add skill_four_level int default 0 not null comment '宠物4技能等级';
-- end


-- start 八一建军节开屏活动 2020-07-14 16:56:23

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

UPDATE `f_cfg` SET `value` = '{\"farm\":[{\"name\":\"\",\"path\":\"\",\"icon\":\"\"}],\"group\":[{\"name\":\"618狂欢\",\"path\":\"/pages/active/active618\",\"icon\":\"/static/image/activity/activity_item.png\",\"start_time\":\"2020/06/07 00:00:00\",\"end_time\":\"2020/06/23 00:00:00\",\"status\":1},{\"name\":\"最美军装\",\"path\":\"/pages/open/rank\",\"icon\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GCJoNu16zcgxj3gUbIRdCyP8Rw0OUdaBvy1DrAkiaP2T6NwUyAtbyXib6ZiaYTTc6DicWIcQKibGmT2QA/0\",\"start_time\":\"2020/07/01 00:00:00\",\"end_time\":\"2020/07/27 00:00:00\",\"status\":1}],\"user\":[{\"name\":\"任务\",\"path\":\"/pages/task/task\",\"icon\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxg8poPGbPbBhXGBtzqccQicFtFiaMzq8O2yB0fVKsIziaJNSFR5c56g8lw/0\"}]}' WHERE `key` = 'btn_cfg';
INSERT INTO `f_cfg`(`description`, `key`, `value`, `show`, `create_time`, `update_time`, `delete_time`) VALUES ('开屏活动配置', 'open', '{\"current\":\"81soldier\",\"content\":{\"81soldier\":{\"banner\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0\",\"help\":{\"article\":51,\"label\":\"活动说明\"},\"time\":{\"end\":\"2020-07-27\"}}}}', 1, '2020-07-16 09:55:05', '2020-07-16 15:24:51', NULL);

--- end

-- start 福袋活动 2020-07-17 10:07:34

-- ----------------------------
-- Table structure for f_cfg_weal_activity_task
-- ----------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='任务-福袋活动';

-- ----------------------------
-- Table structure for f_rec_weal_activity
-- ----------------------------
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

-- ----------------------------
-- Table structure for f_rec_weal_activity_task
-- ----------------------------
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

UPDATE `f_cfg` SET `value` = '{\"farm\":[{\"name\":\"\",\"path\":\"\",\"icon\":\"\"}],\"group\":[{\"name\":\"618狂欢\",\"path\":\"/pages/active/active618\",\"icon\":\"/static/image/activity/activity_item.png\",\"start_time\":\"2020/06/07 00:00:00\",\"end_time\":\"2020/06/23 00:00:00\",\"status\":1},{\"name\":\"夏日福袋\",\"path\":\"/pages/active/weal\",\"icon\":\"/static/image/activity/activity_item.png\",\"start_time\":\"2020/07/07 00:00:00\",\"end_time\":\"2020/08/23 00:00:00\",\"status\":1},{\"name\":\"最美军装\",\"path\":\"/pages/open/rank\",\"icon\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GCJoNu16zcgxj3gUbIRdCyP8Rw0OUdaBvy1DrAkiaP2T6NwUyAtbyXib6ZiaYTTc6DicWIcQKibGmT2QA/0\",\"start_time\":\"2020/07/01 00:00:00\",\"end_time\":\"2020/07/27 00:00:00\",\"status\":1}],\"user\":[{\"name\":\"任务\",\"path\":\"/pages/task/task\",\"icon\":\"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxg8poPGbPbBhXGBtzqccQicFtFiaMzq8O2yB0fVKsIziaJNSFR5c56g8lw/0\"}]}' WHERE `key` = 'btn_cfg';

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

-- end
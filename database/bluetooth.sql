/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50709
Source Host           : 127.0.0.1:3306
Source Database       : bluetooth

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2018-01-12 21:49:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for course_check
-- ----------------------------
DROP TABLE IF EXISTS `course_check`;
CREATE TABLE `course_check` (
  `ccid` int(11) NOT NULL AUTO_INCREMENT,
  `stuNum` varchar(20) NOT NULL,
  `stuName` varchar(255) DEFAULT NULL,
  `trid` varchar(20) DEFAULT NULL,
  `jxbID` varchar(20) NOT NULL COMMENT '课程号',
  `course` varchar(30) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `month` int(10) DEFAULT NULL,
  `week` tinyint(4) NOT NULL COMMENT '周几',
  `hash_day` int(10) NOT NULL COMMENT '课程名',
  `hash_lesson` int(10) NOT NULL,
  `major` varchar(10) DEFAULT NULL,
  `grade` int(10) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `scNum` varchar(20) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '枚举1签到2请假3旷课4迟到5早退9其他',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ccid`,`stuNum`,`jxbID`,`year`,`week`,`hash_day`,`hash_lesson`)
) ENGINE=MyISAM AUTO_INCREMENT=1607 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for course_stu_list
-- ----------------------------
DROP TABLE IF EXISTS `course_stu_list`;
CREATE TABLE `course_stu_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jxbID` varchar(20) NOT NULL,
  `year` int(10) DEFAULT NULL,
  `stu_list` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`jxbID`)
) ENGINE=MyISAM AUTO_INCREMENT=6680 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `stu_code` varchar(20) NOT NULL COMMENT '学生学号',
  `password` varchar(255) DEFAULT NULL,
  `stuName` varchar(255) DEFAULT NULL COMMENT '姓名',
  `idNum` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL COMMENT '性别',
  `stuAcad` varchar(255) DEFAULT NULL COMMENT '学生系别',
  `stuMajor` varchar(255) DEFAULT NULL COMMENT '学生专业',
  `stuGrade` varchar(255) DEFAULT NULL COMMENT '学生年级',
  `stuClass` varchar(255) DEFAULT NULL COMMENT '学生班级',
  `imageUrl` varchar(255) DEFAULT NULL COMMENT '头像地址',
  `remember_token` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`sid`,`stu_code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for student_course
-- ----------------------------
DROP TABLE IF EXISTS `student_course`;
CREATE TABLE `student_course` (
  `scid` int(11) NOT NULL AUTO_INCREMENT,
  `stuNum` varchar(20) NOT NULL,
  `scNum` varchar(20) NOT NULL,
  `stuName` varchar(255) DEFAULT NULL,
  `classRoom` varchar(10) NOT NULL,
  `day` varchar(255) DEFAULT NULL COMMENT '周几表示',
  `hashDay` varchar(255) DEFAULT NULL COMMENT '周几',
  `hashLesson` tinyint(4) DEFAULT NULL COMMENT '课程节数',
  `lesson` varchar(255) DEFAULT NULL COMMENT '课程时间',
  `course` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `period` varchar(255) DEFAULT NULL COMMENT '课程维持节数',
  `rawWeek` varchar(255) DEFAULT NULL COMMENT '课程维持周数',
  `status` varchar(255) DEFAULT NULL,
  `teacher` varchar(255) DEFAULT NULL COMMENT '教师名',
  `type` varchar(255) DEFAULT NULL COMMENT '课程类型',
  `week` tinyint(4) DEFAULT NULL COMMENT '课程周数',
  `weekBegin` int(11) DEFAULT NULL COMMENT '课程开始周数',
  `weekEnd` int(11) DEFAULT NULL COMMENT '结束周数',
  `weekModel` varchar(255) DEFAULT NULL COMMENT '课程周数类型',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`scid`,`stuNum`,`scNum`,`classRoom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for teachers
-- ----------------------------
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `trid` varchar(20) NOT NULL COMMENT '教师ID',
  `tName` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `tAcad` varchar(255) DEFAULT NULL COMMENT '教研室',
  `AcadName` varchar(255) DEFAULT NULL COMMENT '教研室名称',
  `tMajor` varchar(255) DEFAULT NULL COMMENT '院系',
  `tPosition` varchar(255) DEFAULT NULL COMMENT '教室职称',
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tid`,`trid`)
) ENGINE=InnoDB AUTO_INCREMENT=1649 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for teacher_course
-- ----------------------------
DROP TABLE IF EXISTS `teacher_course`;
CREATE TABLE `teacher_course` (
  `tcid` int(11) NOT NULL AUTO_INCREMENT,
  `trid` varchar(20) NOT NULL,
  `scNum` varchar(20) NOT NULL,
  `jxbID` varchar(20) NOT NULL COMMENT '教学班ID',
  `year` int(11) DEFAULT NULL,
  `hash_day` tinyint(4) DEFAULT NULL COMMENT '周几',
  `hash_lesson` tinyint(4) DEFAULT NULL COMMENT '课程节数',
  `begin_lesson` tinyint(4) DEFAULT NULL,
  `day` varchar(255) DEFAULT NULL COMMENT '周几表示',
  `lesson` varchar(255) DEFAULT NULL COMMENT '课程时间',
  `course` varchar(255) DEFAULT NULL,
  `teacher` varchar(255) DEFAULT NULL COMMENT '教师名',
  `type` varchar(255) DEFAULT NULL,
  `classroom` varchar(255) DEFAULT NULL COMMENT '教室',
  `rawWeek` varchar(255) DEFAULT NULL COMMENT '课程维持周数',
  `period` tinyint(4) DEFAULT NULL COMMENT '上课持续时间',
  `week` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tcid`,`trid`,`scNum`,`jxbID`)
) ENGINE=MyISAM AUTO_INCREMENT=12653 DEFAULT CHARSET=utf8mb4;

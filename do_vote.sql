SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫： `esimtw`
--

-- --------------------------------------------------------

--
-- 資料表結構 `do_vote`
--

CREATE TABLE IF NOT EXISTS `do_vote` (
`do_vote_id` int(11) NOT NULL,
  `vote_hash_key` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `vote_bid` int(11) NOT NULL COMMENT 'do 或 group 的 id',
  `vote_multiple` tinyint(1) NOT NULL COMMENT '判斷是否可複選',
  `vote_option_id` int(11) NOT NULL COMMENT '選項的連結',
  `vote_content` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '問題敘述',
  `vote_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '判斷是否被刪除，是 = true，否 = false',
  `vote_deadline` int(11) DEFAULT NULL COMMENT '是否有投票時間限制，如有則為截止時間',
  `vote_last_modify_time` int(11) NOT NULL COMMENT '最後修改日期',
  `vote_last_modify_ip` int(11) NOT NULL COMMENT '最後修改 IP',
  `cvote_time` int(11) NOT NULL COMMENT '創建時間',
  `vote_ip` int(11) NOT NULL COMMENT '創建 IP'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `do_vote`
--
ALTER TABLE `do_vote`
 ADD PRIMARY KEY (`do_vote_id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `do_vote`
--
ALTER TABLE `do_vote`
MODIFY `do_vote_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

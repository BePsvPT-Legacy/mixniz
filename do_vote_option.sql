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
-- 資料表結構 `do_vote_option`
--

CREATE TABLE IF NOT EXISTS `do_vote_option` (
`id` int(11) NOT NULL,
  `vote_option_id` int(11) NOT NULL,
  `vote_option_hash_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `vote_option_content` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '選項敘述',
  `vote_option_voted_user` longtext COLLATE utf8_unicode_ci COMMENT '投票用戶',
  `vote_option_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '此選項是否被刪除，是 = true，否 = false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `do_vote_option`
--
ALTER TABLE `do_vote_option`
 ADD PRIMARY KEY (`id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `do_vote_option`
--
ALTER TABLE `do_vote_option`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

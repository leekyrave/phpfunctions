<?php 
 /**
 * Get User Permission
 * @param int $id — user's vk id
 * @param int $lvl — compared value
 * @param object $vk - library
 * @return bool
 *
 */

function isPermission($id, $lvl, $vk)
{
  $getdostyp = R::findone('users', 'user_id = ?', [$id]);
  $getdostyp = $getdostyp->dostyp;
  if ($getdostyp >= $lvl) {
    return true;
  } else {
    $vk->sendMessage($peer_id, "Данная команда доступна с " . $lvl . " уровня доступа. У вас: " . $getdostyp . ".");
    return false;
  }
}

/**
 * Get User Permission
 * @param int $id — user's vk id
 * @return int
 *
 */

function getPermission($id)
{
  $getdostyp = R::findone('users', 'user_id = ?', [$id]);
  if ($getdostyp) {
    return $getdostyp->dostyp;
  } else {
    return 1;
  }
}


/**
 * Cooldown between cmds or sending text
 * @param int $id — user's vk id
 * @param object $vk — library
 * @param int $peer_id — user_id/conversation_id
 * @return bool
 *
 */

function timecmd($id,$vk,$peer_id)
{
  $cd = 10;
  $timecmd = R::findone('users', 'user_id = ?', [$id]);
  if ($timecmd) {
    $resultcd = time() - $timecmd->cooldown;
    if ($resultcd >= $cd) {
      $timecmd->cooldown = time();
      R::store($timecmd);
      return true;
    } else {
      $resultcd = $cd - $resultcd;
      
      $vk->sendMessage($peer_id, "Не так быстро,я могу перегреться!\nПодождите еще $resultcd ". numberof($resultcd, 'секун', array('ду', 'ды', 'д')) . ".");
      return false;
    }
  }
}

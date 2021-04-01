<?php 
 /**
 * Get User Permission
 * @param int $id Ч user's vk id
 * @param int $lvl Ч compared value
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
    $vk->sendMessage($peer_id, "ƒанна€ команда доступна с " . $lvl . " уровн€ доступа. ” вас: " . $getdostyp . ".");
    return false;
  }
}

/**
 * Get User Permission
 * @param int $id Ч user's vk id
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
 * @param int $id Ч user's vk id
 * @param object $vk Ч library
 * @param int $peer_id Ч user_id/conversation_id
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
      
      $vk->sendMessage($peer_id, "Ќе так быстро,€ могу перегретьс€!\nѕодождите еще $resultcd ". numberof($resultcd, 'секун', array('ду', 'ды', 'д')) . ".");
      return false;
    }
  }
}
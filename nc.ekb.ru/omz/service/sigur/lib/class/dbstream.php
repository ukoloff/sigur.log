<?
//
// DB Stream of records
//
class dbStream
{
  function __construct($src)
  {
    $this->src = $src;
    $this->it = null;
    $this->EOF = 0;
  }

  function get()
  {
    if ($this->it):
      $it = $this->it;
      $this->it = null;
      return $it;
    endif;

    if ($this->EOF)
      return null;

    $it = $this->src->fetchObject();
    if (!$it)
      $this->EOF = 1;
    return $it;
  }

  function unget($it)
  {
    $this->it = $it;
  }

  function fetchObject()
  {
    return $this->get();
  }
}

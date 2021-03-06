<?
namespace Tbot\Message;

/**
 * Interface IMessage
 */
interface IMessage
{
   public function __construct($data);
   public function getMessageId();
   public function getChatId();
   public function getDataRaw();
   public function getText();
   public function setText($value);
}
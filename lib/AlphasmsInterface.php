<?php
namespace Werkint\Alphasms;

/**
 * AlphasmsInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface AlphasmsInterface
{
    /**
     * Отправляет сообщение
     * @param string $target Номер клиента
     * @param string $message Текст сообщения
     * @param string $sender Имя отправителя
     * @param \DateTime $sendDate Время/дата отправки
     * @param bool $flash Flash-сообщение
     * @return Response
     */
    public function sendMessage(
        $target,
        $message,
        $sender,
        \DateTime $sendDate = null,
        $flash = false
    );

    /**
     * Получает статус SMS
     * @param int $id
     * @return Response
     */
    public function getMessageStatus($id);

    /**
     * Возвращает баланс
     * @return Response
     */
    public function getBalance();
}
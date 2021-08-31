<?php

/*
Файлы
*/

namespace Tbot\Message;


class Document extends Message
{

    /**
     * Получить Название файла
     * @return string
     */
    public function getFileName()
    {
        if (isset($this->data['message']['document']['file_name'])) {
            return $this->data['message']['document']['file_name'];
        }
        return '';
    }

    /**
     * Получить MIME файла
     * @return string
     */
    public function getMimeType()
    {
        if (isset($this->data['message']['document']['mime_type'])) {
            return $this->data['message']['document']['mime_type'];
        }
        return '';
    }

    /**
     * Получить file_id файла
     * @return string
     */
    public function getFileId()
    {
        if (isset($this->data['message']['document']['file_id'])) {
            return $this->data['message']['document']['file_id'];
        }
        return '';
    }

    /**
     * Получить file_unique_id файла
     * @return string
     */
    public function getFileUniqueId()
    {
        if (isset($this->data['message']['document']['file_unique_id'])) {
            return $this->data['message']['document']['file_unique_id'];
        }
        return '';
    }

    /**
     * Получить размер файла
     * @return string
     */
    public function getFileSiza()
    {
        if (isset($this->data['message']['document']['file_size'])) {
            return $this->data['message']['document']['file_size'];
        }
        return '';
    }
}
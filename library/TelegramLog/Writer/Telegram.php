<?php
/**
 * Writer in Telegram for Zend Log
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Ilya Serdyuk <ilya@serdyuk.pro>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class TelegramLog_Writer_Telegram extends Zend_Log_Writer_Abstract
{
    /**
     * @var string Telegram Bot API Access Token
     */
    protected $_accessToken;

    /**
     * @var array
     */
    protected $_params = [];

    /**
     * @var \Telegram\Bot\Api Instance of Telegram API
     */
    protected $_api;

    /**
     * Class constructor
     *
     * @param array $params
     * @return void
     */
    public function __construct(array $params)
    {
        if (array_key_exists('token', $params)) {
            $this->_accessToken = $params['token'];
            empty($params['token']);
        }

        $options = array (
            'chat_id',
            'parse_mode',
            'disable_web_page_preview',
            'disable_notification',
            'reply_to_message_id',
            'reply_markup',
        );
        foreach ($options as $option) {
            if (array_key_exists($option, $params)) {
                $this->_params[$option] = $params[$option];
            }
        }
    }

    /**
     * Construct a Zend_Log driver
     *
     * @param  array|Zend_Config $config
     * @return Zend_Log_FactoryInterface
     */
    public static function factory($config)
    {
        $config = self::_parseConfig($config);

        if (empty($config['chat_id'])) {
            throw new Exception('Chat ID unknown');
        }

        return new self($config);
    }

    /**
     * Write a message to the log
     *
     * @param  array $event  event data
     * @return void
     */
    protected function _write($event)
    {
        $params = $this->_params;

        if ($this->_formatter instanceof Zend_Log_Formatter_Interface) {
            $params['text'] = $this->_formatter->format($event);
        } else {
            $params['text'] = $event['message'];
        }

        if (empty($this->_api)) {
            $this->_api = new \Telegram\Bot\Api($this->_accessToken);
        }
        $this->_api->sendMessage($params);
    }
}

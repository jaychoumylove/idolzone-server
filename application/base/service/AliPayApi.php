<?php


namespace app\base\service;


use app\base\service\alipay\AopClient;

class AliPayApi
{
    public static function getInstance()
    {
        $aop = new AopClient ();

        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2021001188684845';
        $aop->rsaPrivateKey = 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCwkDA/lQXLrYLzhEMRBmR5xmbcW9c2hrjBMlaDlkZkCxsueya2tMLcqFcP4s8qdAhh8ZPT59vwu+nxfHxy6Ji3vc95JHb+yCWqes6pRMKmLZMPesoKxjdZ2UZzGRpfbguaihnI7JDANSJMPly+mlzkGkza6XcmNeS129pfcYGXl0tVtGl3fGu/6EWpiFFNyCcuUQaOY2A0SP6fKUPqseaYGn0Uo6Jk/F7qfvyfLpKqe27VvGc6GiU6EZXQz9qLHxUzMxaVcrk/ETKeLykRNL+WAf0DaoaNtP9u0zC8QAepaJ6u5xU8wwbORHCcNC0oA+gQPUHuGax3KvE+DqzF9wQxAgMBAAECggEAGMAts0wNou6w07gvupw9y4BPfQZ21dLu5U5MKGgReAbCibKDy8rtIgeLJNPznIzle+1kfqkbANUmx05fF7CZb/BwvBergq3F8e8DN2FHNApheiMJXZdJvROjN5FGTCheAgzn7m2TIQ6Rv0RitelnqiYxreJabIXTiNbH9ucvdnNoTE2OhdfNqTHP8/nIA88LFJ4d6NE07vw1xUjjgjHemMHM3j48i0ITjGoq8wEBoBZUCft/2/HZBD/muvFLU8da8akm8JxV/3rLTcix52PzNXjtUqZH+wTrtl/kGhUIJ146gvLngjYClHgjiSZWHB2WVH0oZDYcsdYr7xk0k8mZUQKBgQDhQ3IIkL0p7hkOPE6rrEyjN2NY3uWRRnwSLatejTsAvgVE89MGKHPNcWkbYAQfhbb3Fj49A1ysaImcGjj3eapU8ovlmWyoz2eYfMVZJ1GIoIYE5Npg0jUZe7mEt+mWS/iEOi8q1VQCBpGqQcCW4B7CsPIlnyuklbg4Kqdm6A10zQKBgQDIp59JbXCNDm52qMf0x7ZXsh+D3H2tCgYxpbidPzhKO9Lo7u7LZh6Lqc1dIb26pM9/vJuRP3hd3q+Zxty11yuco9n5LTLTuPRX4NI7lVkKgHIOCI92CEWOMWsjJH2ddwH7fhHTIhcGfo8ww3O/OwB4NtG0P5b7UJROpmrurQ8s9QKBgBqm6jUNOvRdEWXXyM8TAbZF9WEsbij0F/XmpWH4f8SktubjmlIeMyVZh8APai14mp89aHu7jBlx/OYVTCwrnvWSBO9TacHHWFB4Yrkbp8/sfi7SDOxrspCjTN3hDKgVsP+kCrScrOXYdR+Zy/mN5tXOLbg5zuYYOMWcdlX8mqr5AoGAWKCJH7KiGvu72Nd18m1f4d5AQ0rZi9u4nqc3IuVwpIFGCDK48Mg7R68JkVA88DqpmB8ji4VAUh3w6/hFNmBC2B4bQg8vuwqfik04Lq8ptBTFy+MGErlWl5bGXrBYd3vAOWgW0W3mQ60U7BH4hwe1jmOQPpAzzO9rgk3JP4DbCh0CgYBcF8UAN8Svq5duOU5NHHJFXq9bhtLKNo6RwmWtHZKnZG1N3+FHT7fcK4FTKA3H7zIZWaxJXHjo022kgWZyDjaGlNpZR7efmsFErJ3wSawi9UkLaartYIot7fqjoLnXe2pljXEWTkySpV0OvmgnAWPXZmoG4LhsXe7XkhhWyvKXPA==';
        $aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqzRH1LLgPRnaqYvgnmvprA6H201AAGhIhiuX6UgH9rlAqaWSjxk3vq7EULVpCOqed4FCdUrJKQkI6Fq9PW+XPSI/YbmBxKFKt/Q5VrAIWJ+AZu0zzTiunLwsrPIl8DYeBDIqa0Nl5R68SMGcAFuPQ/vKMr6YcqdQpRXOSqdp1RBh92y3sdxcP0/JtvujvwMAY7MUHLx9MGQSAiuRVlv+rEUVOYMiLsza4S2mdRGXdliM73SUyI1k9fGICa2dUrhFsvg98d6+17VDHH4JC8ibcgpTbxhl4swRO+YK+85iFtaW++KILKXzBayqe0mNxI+maNcc8goDhE82eF7WQE+vjQIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';

        return $aop;
    }
}
<?php

namespace App\Constants;

class Status
{

    const ENABLE  = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO  = 0;

    const VERIFIED   = 1;
    const UNVERIFIED = 0;

    const PLAN_ENABLE  = 1;
    const PLAN_DISABLE = 0;

    const PLAN_POPULAR_ENABLE  = 1;
    const PLAN_POPULAR_DISABLE = 0;

    const PLAN_SUBSCRIPTION_INITIAL  = 0;
    const PLAN_SUBSCRIPTION_APPROVED = 1;
    const PLAN_SUBSCRIPTION_PENDING  = 2;
    const PLAN_SUBSCRIPTION_REJECT   = 3;
    const PLAN_SUBSCRIPTION_DISABLE  = 4;


    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_REJECT   = 3;

    const FORM_BUILDER_DISABLE = 0;
    const FORM_BUILDER_ENABLE  = 1;


    const FORM_BUILDER_ANSWER_APPROVED = 1;
    const FORM_BUILDER_ANSWER_PENDING  = 2;
    const FORM_BUILDER_ANSWER_REJECTED = 3;


    const CREDIT_PAYMENT_INITIAL      = 0;
    const CREDIT_PAYMENT_APPROVED     = 1;
    const CREDIT_PAYMENT_PENDING      = 2;
    const CREDIT_PAYMENT_REJECT       = 3;
    const CREDIT_PAYMENT_WITH_BALANCE = 4;


    const TICKET_OPEN   = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY  = 2;
    const TICKET_CLOSE  = 3;

    const PRIORITY_LOW    = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH   = 3;

    const USER_ACTIVE = 1;
    const USER_BAN    = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_VERIFIED   = 1;
    const KYC_PENDING    = 2;

    const ROLE_TYPE_ADMIN = 1;
    const ROLE_TYPE_USER  = 2;

    const REG_COMPLETED = 1;
    const REG_PENDING   = 0;


    const SYSTEM_LINK   = 1;
    const EXTERNAL_LINK = 2;
    const PAGE_LINK     = 3;
}

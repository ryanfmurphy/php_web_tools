<?php
class Quote extends Model {
    /*
    create table quote ( id integer primary key auto_increment
        , company_id integer
        , price decimal
        , `change` decimal
        , open_price decimal
        , high_price decimal
        , low_price decimal
        , bid_quantity integer -- Number of shares available at the Market Center's bid price in a given security   Yes
        , bid_price decimal    -- The highest price at the Market Center that someone is willing to buy a security at the given time    Yes
        , ask_quantity integer -- Number of shares available at the Market Center's ask price in a given security   Yes
        , ask_price decimal    -- The lowest price at the Market Center that someone is willing to sell a security at a given time  Yes
        , quote_condition varchar(2046) -- Quote Condition Code(s)
        , time timestamp default current_timestamp    , end_time timestamp
        , market_center varchar(2046)   -- Indicates the Market center code that originated the message Yes
        , volume real
    );
    */
}

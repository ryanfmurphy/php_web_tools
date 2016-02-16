<?php
class QuoteView extends Quote {
    /*
    select q.id, c.symbol, c.name, q.price, q.change
        , q.open_price, q.high_price, q.low_price
        , q.company_id, q.time, q.end_time
        , c.sector, c.type
    from quote as q
        join company as c
        on c.id = q.company_id
    */
}
?>


<?php

class Pagination
{
    /**
     * Anything else before page number.
     * @example news.php?id=2&page=
     **/
    var $link;
    
    /**
     * Total records to count.
     * Used to generate last etc.
     **/
    var $total;
    
    /**
     * How many records to display per page.
     **/
    var $display = 10;
    
    /**
     * Current page.
     **/
    var $current;
    
    /**
     * Holds numbers for SQL query.
     **/
    var $limit = array();
    
    /**
     * Hold number of total pages.
     **/
    var $total_pages;
    
    /**
     * Seperate links for First, Prev., Next, Last.
     **/
    var $first;
    var $last;
    var $prev;
    var $next;
    
    /**
     * Seperate links for numbers.
     **/
    var $pages;
    
    /**
     * constructor
     * @var $link string Anything before page number.
     * @var $total int Total number of records.
     **/
    function Pagination($link = null, $total = null)
    {
        $this->set_link($link);
        $this->set_total($total);
        $this->get_current_page();
        $this->calc_limits();
    }
    
    /**
     * Sets how many records to display.
     **/
    function set_display($display)
    {
        $tmp = intval($display);
        
        if($tmp > 0) $this->display = $tmp;
        
        $this->calc_limits();
    }
    
    /**
     * Sets link
     **/
    function set_link($link)
    {
        $this->link = $link;
    }
    
    /**
     * Sets total, duh
     **/
    function set_total($total)
    {
        $this->total = intval($total);
    }
    
    /**
     * Main function. Generates the links and returns links.
     * @return string links for pages.
     **/
    function paginate()
    {
        $this->total_pages = ceil($this->total / $this->display);
        
        if($this->total_pages == 1)
        {
            $this->first = '';
            $this->prev = '';
            $this->next = '';
            $this->last = '';
        }
        else
        {
            if($this->current == 1) $this->first = '&laquo; First';
            else $this->first = '<a href="' . $this->link . '1">&laquo; First</a>';
            
            if($this->total_pages > 1)
            {
                if($this->current > 1) $this->prev = '<a href="' . $this->link . ($this->current - 1) . '">&lsaquo; Prev.</a>';
                else $this->prev = '&lsaquo; Prev.';
                
                if(($this->current - 2) >= 1)
                {
                    if(($this->current - 2) > 1) $this->pages = '... ';
                    
                    $this->pages .= '<a href="' . $this->link . ($this->current - 2) . '">' . ($this->current - 2) . '</a> ';
                    $this->pages .= '<a href="' . $this->link . ($this->current - 1) . '">' . ($this->current - 1) . '</a> ';
                }
                else if(($this->current - 1) >= 1) $this->pages .= '<a href="' . $this->link . ($this->current - 1) . '">' . ($this->current - 1) . '</a> ';
                            
                $this->pages .= $this->current . ' ';
                
                if(($this->current + 2) <= $this->total_pages)
                {
                    $this->pages .= '<a href="' . $this->link . ($this->current + 1) . '">' . ($this->current + 1) . '</a> ';
                    $this->pages .= '<a href="' . $this->link . ($this->current + 2) . '">' . ($this->current + 2) . '</a> ';
                    
                    if(($this->current + 2) < $this->total_pages) $this->pages .= '...';
                }
                else if(($this->current + 1) <= $this->total_pages) $this->pages .= '<a href="' . $this->link . ($this->current + 1) . '">' . ($this->current + 1) . '</a> ';
                
                if($this->current < $this->total_pages) $this->next = '<a href="' . $this->link . ($this->current + 1) . '">Next &rsaquo;</a>';
                else $this->next = 'Next &rsaquo;';
            }
            
            if($this->current == $this->total_pages) $this->last = 'Last &raquo;';
            else $this->last = '<a href="' . $this->link . $this->total_pages . '">Last &raquo;</a>';
        }
        
        return $this->first . ' ' . $this->prev . ' ' . $this->pages . ' ' . $this->next . ' ' . $this->last;
    }
    
    /**
     * Get's the current page from get method and fills out $this->current
     **/
    private function get_current_page()
    {
        if(isset($_GET['page']))
        {
            if(is_int($_GET['page'])) $this->current = $_GET['page'];
            else if(intval($_GET['page']) == 0) $this->current = 1;
            else $this->current = intval($_GET['page']);
        }
        else $this->current = 1;
    }
    
    private function calc_limits()
    {
        $this->limit['limit1'] = $this->current * $this->display - $this->display;
        $this->limit['limit2'] = $this->display;
    }
}
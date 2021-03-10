<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Calendar
{
    protected $CI;

    private $_initial_date = null;
    private $_final_date = null;
    private $_events_data = null;
    private $_initial_date_events_data = null;
    private $_final_date_events_data = null;
     
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function init($initial_date, $final_date, $events_data)
    {
        if ($initial_date && $final_date && $events_data && count($events_data) > 1) {
            $this->_initial_date = $initial_date;
            $this->_final_date = $final_date;
            $this->_events_data = $events_data;
            $this->_initial_date_events_data = $events_data[0]->dataplantao;
            $this->_final_date_events_data = $events_data[count($events_data)-1]->dataplantao;
        } else {
            return false;
        }

        return true;
    }

    public function generate()
    {
        $week_day_start = $this->_weekDay($this->_initial_date);
        if ($this->_initial_date != $this->_initial_date_events_data) {
            $week_day_start = $this->_weekDay($this->_initial_date_events_data);
        }
        $initial_date_fmt = date("d/m/Y", strtotime($this->_initial_date));
        $final_date_fmt = date("d/m/Y", strtotime($this->_final_date));

        if ($week_day_start > 0) {
            foreach (range(1, $week_day_start) as $key => $value) {
                $dias = ' - ' . ($value) . ' days';
                $data = date('Y-m-d', strtotime($this->_initial_date . $dias));

                $dia = new stdClass();
                $dia->id = $value;
                $dia->dataplantao = $data;
                $dia->horainicialplantao = null;
                $dia->nome_profissional = null;
               
                array_unshift($this->_events_data, $dia);
            }
        }

        $eventos = $this->_groupBy("dataplantao", $this->_events_data);

        $num_days = $this->_daysInPeriod($this->_initial_date, $this->_final_date);
        //$num_days2 = count($eventos);
        $total_days = $num_days + $week_day_start;
        $num_weeks = ceil($total_days / 7);

        /*
        echo("Quantidade de eventos: ");
        echo(count($eventos));
        echo("<br>");
        var_dump($eventos);
        echo("<br>");
        */

        $begin = "
            <table id='calendar'>
            <caption>$initial_date_fmt a $final_date_fmt</caption>
            <tr class='weekdays'>
                <th scope='col'>Domingo</th>
                <th scope='col'>Segunda</th>
                <th scope='col'>Terça</th>
                <th scope='col'>Quarta</th>
                <th scope='col'>Quinta</th>
                <th scope='col'>Sexta</th>
                <th scope='col'>Sábado</th>
            </tr>";

        $end = "</table>";
        
        $evts = array_chunk($eventos, 7, true);

        $calendar = "";
        $calendar .= $begin;
        foreach (range(1, $num_weeks) as $key) {
            $events = isset($evts[$key-1]) ? $evts[$key-1] : null;
            $calendar .= $this->_makeWeek($key, $events);
        }
        $calendar .= $end;

        //exit;
        return $calendar;
    }

    private function _makeWeek($num_week, $week_data)
    {
        $week = "<tr class='days'>";
        if ($week_data) {
            foreach ($week_data as $day) {
                $week .= $this->_makeDay($day);
            }
        }
        $week .= "</tr>";

        return $week;
    }

    private function _makeDay($day_data)
    {
        $num_day = 0;
        if (sizeof($day_data) > 0) {
            $num_day = date('d', strtotime($day_data[0]->dataplantao));
        }
        $day = "<td class='day'>
        <div class='date'>$num_day</div>";

        foreach ($day_data as $event) {
            if (!$event->nome_profissional) {
                $event->nome_profissional = '';
            }
            $hora_inicial_fmt = date("H:i", strtotime($event->horainicialplantao));
            $event_fmt = $hora_inicial_fmt . $event->nome_profissional;
            $turno = '';
            if ((int)$hora_inicial_fmt >= 5 and (int)$hora_inicial_fmt < 13) {
                $turno = 'M';
                $turno_class = 'turno_manha';
            }
            if ((int)$hora_inicial_fmt >= 13 and (int)$hora_inicial_fmt < 19) {
                $turno = 'T';
                $turno_class = 'turno_tarde';
            }
            if ((int)$hora_inicial_fmt >= 19 and (int)$hora_inicial_fmt <= 23) {
                $turno = 'N';
                $turno_class = 'turno_noite';
            }
            $day .= "<div class='event'>
                        <div class='event-desc'>
                            <span class='" . $turno_class .  "'>$turno</span>&nbsp;<span class='nome_profissional'>$event->nome_profissional</span>
                        </div>
                    </div>";
        }
        
        $day .= "</td>";

        return $day;
    }

    private function _weekDay($date)
    {
        return date('w', strtotime($date));
    }

    private function _groupBy($key, $data) {
        $result = array();
    
        foreach ($data as $val) {
            if (isset($val->$key)) {
                $result[$val->$key][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }

    private function _daysInPeriod($initial_date, $final_date)
    {
        $datetime1 = date_create($initial_date);
        $datetime2 = date_create($final_date);

        $interval = date_diff($datetime1, $datetime2);
   
        return $interval->format('%a')+1;
    }

    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth($month=null,$year=null)
    {
        if ( null==($year) ) {
            $year =  date("Y", time()); 
        }

        if (null==($month)) {
            $month = date("m", time());
        }

        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month, $year);
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
        $monthEndingDay= date('N', strtotime($year.'-'.$month.'-'.$daysInMonths));
        $monthStartDay = date('N', strtotime($year.'-'.$month.'-01'));

        if ($monthEndingDay < $monthStartDay) {
            $numOfweeks++;
        }

        return $numOfweeks;
    }
 
    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth($month=null, $year=null)
    {
        if (null == ($year)) {
            $year =  date("Y", time());
        }

        if (null == ($month)) {
            $month = date("m", time());
        }

        return date('t', strtotime($year.'-'.$month.'-01'));
    }
}
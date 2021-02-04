<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Calendar
{
    protected $CI;

    const SATURDAY_WEEK_INDEX = 6;

    private $_initial_date = null;
    private $_final_date = null;
    private $_events_data = null;

    private $_debug = false;
     
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
        } else {
            return false;
        }

        if ($this->_debug) {
            echo('<pre>');
            echo('_initial_date: ' . $this->_initial_date . '<br>');
            echo('_final_date: ' . $this->_final_date . '<br>');
            var_dump($this->_events_data);
            echo('</pre>');
            echo('<br><br><br>');
        }

        return true;
    }

    public function generate()
    {
        $week_day_start = $this->_weekDay($this->_initial_date);
        $week_day_end = $this->_weekDay($this->_final_date);
        $initial_date_fmt = date("d/m/Y", strtotime($this->_initial_date));
        $final_date_fmt = date("d/m/Y", strtotime($this->_final_date));

        $begin = new DateTime($this->_initial_date . ' - ' . $week_day_start .  ' days');
        $end = new DateTime($this->_final_date . ' + ' . ($this::SATURDAY_WEEK_INDEX - $week_day_end) .  ' days');

        $days_to_render = array();

        // Vinculando eventos à cada dia do período selecionado
        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
            $day = new stdClass();
            $day->id = $i->format("Y-m-d");
            $day->events = array();
            foreach ($this->_events_data as $key => $event) {
                if ($event->dataplantao == $i->format("Y-m-d")) {
                    array_push($day->events, $event);
                    unset($this->_events_data[$key]);
                }
            }
            array_push($days_to_render, $day);
        }

        // Vinculando eventos à cada dia do período selecionado
        foreach ($days_to_render as $d) {
            if (empty($d->events)) {
                $no_event = new stdClass();
                $no_event->dataplantao = $d->id;
                $d->events[0] = $no_event;
            }
        }

        if ($this->_debug) {
            echo('<pre>');
            var_dump($days_to_render);
            echo('</pre>');
            echo('<br><br><br>');
        }

        $events = $this->_groupBy("id", $days_to_render);
        $num_days = $this->_daysInPeriod($this->_initial_date, $this->_final_date);
        $total_days = $week_day_start + $num_days + ($this::SATURDAY_WEEK_INDEX - $week_day_end);
        $num_weeks = ceil($total_days / 7);

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
        
        $evts = array_chunk($events, 7, true);

        if ($this->_debug) {
            echo('<pre>');
            var_dump($evts);
            echo('</pre>');
            echo('<br><br><br>');
        }

        $calendar = "";
        $calendar .= $begin;
        foreach (range(1, $num_weeks) as $key) {
            $week_events = isset($evts[$key-1]) ? $evts[$key-1] : null;
            $calendar .= $this->_makeWeek($key, $week_events);
        }
        $calendar .= $end;

        return $calendar;
    }

    private function _makeWeek($num_week, $week_data)
    {
        /*
        echo('<pre>');
        var_dump($week_data);
        echo('</pre>');
        echo('<br><br><br>');
        exit;
        */
        
        $week = "<tr class='days'>";
        if ($week_data) {
            foreach ($week_data as $day) {
                $week .= $this->_makeDay($day[0]->events);
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
        if (!property_exists($day_data[0], 'nome_profissional')) {
            $day = "<td class='out_of_period_day'>
            <div class='out_of_period_date'>$num_day</div>";
        }


        foreach ($day_data as $event) {
            // Se não existe plantão nessa data ou a data é anterior ou posterior ao período selecionado,
            // imprime apenas o número do dia e passa para o próximo dia para processar os plantões
            if (!property_exists($event, 'nome_profissional')) {
                //$event->nome_profissional = '------';
                //$event->horainicialplantao = '------';
                continue;
            }
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
   
        return $interval->format('%a') + 1;
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
<template>
  <div>
    <vue-cal
      active-view="month"
      :disable-views="['week', 'day', 'year', 'years']"
      hide-view-selector
      :startWeekOnSunday="true"
      :events-on-month-view="true"
      :today-button="true"
      :events="events"
      :time="false"
      @ready="fetchEvents"
      @view-change="fetchEvents"
    >
      <template #event="{ event, view }">
        <a :href="event.url">
          <div class="vuecal__event-title" v-html="event.title" />
          <small class="vuecal__event-time">
            <span>{{ event.start.formatTime("H:mm") }}</span> - <span>{{ event.end.formatTime("H:mm") }}</span>
          </small>
        </a>
      </template>
    </vue-cal>
  </div>
</template>

<script>
import VueCal from 'vue-cal'

export default {
  components: {
    VueCal
  },
  props: {
    baseUrl: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      events: [],
    }
  },
  methods: {
    fetchEvents({ view, startDate, endDate, week }) {
      let url = new URL(this.baseUrl);

      url.searchParams.append('start', startDate);
      url.searchParams.append('end', endDate);

      fetch(
        url,
        {
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          // body: JSON.stringify({
          //   view,
          //   startDate,
          //   endDate,
          //   week,
          // }),
        }
      ).then((response) => response.json())
      .then((response) => {
        this.events = response.data;
      }).catch((error) => {
        console.log(error);
      });

      // // Do an ajax call here with the given startDate & endDate.
      // // Your API should return an array of events for this date range.
      // // Here we pretend an API call with a Promise and the setTimeout simulates the payload time.
      // new Promise((resolve, reject) => { setTimeout(resolve, 400) })
      //   .then(() => {
      //     switch (view) {
      //       case 'week':
      //         // If week view return the current week from API.
      //         this.events = events[week]
      //         break
      //       case 'month':
      //       case 'day':
      //         // If `month` or `day` view, return all the events from API.
      //         // (But your API should rather return events only for the given date range)
      //         this.events = [...events[44], ...events[45], ...events[46]]
      //         break
      //     }
      //   })
    }
  }

}
</script>

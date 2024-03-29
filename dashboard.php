<?php $page = "Dashboard" ?>
<?php $protected = true ?>
<?php require_once "assets/components/templates/header.php"; ?>
<?php require_once "assets/components/templates/topbar.php"; ?>
<?php require_once "assets/components/templates/sidebar.php"; ?>
<main id="main" class="main">
  <?php
  switch ($_SESSION['role']) {
    case 'Admin':
      $year = $_GET['year'] ?? date('Y');
      ?>
      <form class="">
        <div class="row">
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="input-group mb-3">
              <span class="input-group-text" id="inputGroup-sizing-default">Year</span>
              <select name="year" onchange="this.form.submit()" class="form-select">
                <?php
                for ($i = date('Y'); $i >= 2023; $i--) {
                  ?>
                  <option value="<?= $i ?>" <?= isset($year) && $year == $i ? 'selected' : '' ?>>
                    <?= $i ?>
                  </option>
                  <?php
                }
                ?>
              </select>
            </div>
          </div>
        </div>
      </form>
      <section class="section dashboard">
        <div class="row">

          <div class="col-xxl-4 col-md-6">
            <div class="card info-card" onclick="location='helpdesks.php'">

              <div class="card-body">
                <h5 class="card-title">Assistances</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center text-light"
                    style="background: #11235A">
                    <i class="bi bi-person-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="count_helpdesks"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="col-xxl-4 col-md-6">
            <div class="card info-card" onclick="location='meetings.php'">

              <div class="card-body">
                <h5 class="card-title">Meetings</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center text-light"
                    style="background: #11235A">
                    <i class="bi bi-person-video2"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="count_meetings"></h6>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="col-xxl-4 col-xl-12">

            <div class="card info-card">

              <div class="card-body">
                <h5 class="card-title">Equipment</h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center text-light"
                    style="background: #11235A">
                    <i class="bi bi-pc-display"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="count_equipment"></h6>

                  </div>
                </div>

              </div>
            </div>

          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Assistances by category</h5>
                <!-- Bar Chart -->
                <div id="barChart"></div>
                <!-- End Bar Chart -->

              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Assistances by Division</h5>

                <!-- Donut Chart -->
                <div id="donutChart"></div>
                <!-- End Donut Chart -->

              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Assistances by Sex</h5>

                <!-- Pie Chart -->
                <div id="pieChart"></div>
                <!-- End Pie Chart -->

              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Number of Assistances</h5>

                <!-- Line Chart -->
                <div id="lineChart"></div>
                <!-- End Line Chart -->

              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Zoom Calendar</h5>

                <div id='zoom-calendar'></div>

              </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="editModalLabel"></h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="user-edit">

                    <form id="editmeeting" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>

                      <div class="mb-1">
                        <label for="date_requested">Date Requested</label>
                        <input name="date_requested" type="date" class="form-control form-control-sm" id="date_requested"
                          value="<?= date('Y-m-d') ?>" required />
                      </div>

                      <div class="mb-1">
                        <label for="topic">Meeting topic or title</label>
                        <div class="text-end">
                          <textarea name="topic" class="form-control form-control-sm" id="topic" maxlength="150"
                            required></textarea>
                          <div id="the-count">
                            <span id="current">0</span>
                            <span id="maximum">/ 150</span>
                          </div>

                          <script>
                            $(document).ready(function () {
                              $('#editmeeting #topic').on('keyup', function () {
                                var currentLength = $(this).val().length;
                                $('#editmeeting #current').text(currentLength);
                              });
                            });
                          </script>
                        </div>
                      </div>

                      <div class="mb-1">
                        <label for="date_scheduled">Date of schedule</label>
                        <input name="date_scheduled" type="date" class="form-control form-control-sm" id="date_scheduled"
                          value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required />
                      </div>

                      <div class="mb-1">
                        <label for="time_start">Time of schedule starts</label>
                        <input name="time_start" type="time" class="form-control form-control-sm" id="time_start"
                          value="<?= date('H:i') ?>" required />
                      </div>

                      <div class="mb-1">
                        <label for="time_end">Time of schedule ends</label>
                        <input name="time_end" type="time" class="form-control form-control-sm" id="time_end"
                          value="<?= date('H:i') ?>" required />
                      </div>
                      <div class="mb-1">
                        <label for="status_id">Meeting status</label>
                        <select name="status_id" class="form-select form-select-sm" id="status_id">
                          <?php
                          $query = $conn->query("SELECT * FROM meetings_statuses");
                          while ($row = $query->fetch_object()) {
                            ?>
                            <option value="<?= $row->id ?>">
                              <?= $row->status ?>
                            </option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>

                      <div class="mb-1">
                        <label for="host_id">Hosted by</label>
                        <select name="host_id" class="form-select form-select-sm" id="host_id">
                          <option value="" selected disabled>--</option>
                          <?php
                          $query = $conn->query("SELECT * FROM hosts");
                          while ($row = $query->fetch_object()) {
                            ?>
                            <option value="<?= $row->id ?>">
                              <?= $row->host_name ?>
                            </option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>

                      <div class="mb-1">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" class="form-control form-control-sm" id="remarks" rows="10"></textarea>
                      </div>
                      <div hidden>
                        <input name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
                        <input class="captcha-token" name="captcha-token" />
                        <input name="id" id="id" />
                        <input name="edit_meeting" />
                        <input type="submit" id="editform" />
                      </div>
                    </form>

                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" onclick="editform.click()">Save changes</button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

      <script>
        $(document).ready(function () {
          var calendarEl = $("#zoom-calendar")[0];
          
          var initialView = $(window).width() < 768 ? "listMonth" : "dayGridMonth";

          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: initialView,
            events: 'assets/components/includes/meetings.php',
            dayMaxEventRows: 1,
            eventTimeFormat: {
              hour: '2-digit',
              minute: '2-digit',
              hour12: true
            },
            headerToolbar: {
              start: 'title',
              center: '',
              end: 'prev,next'
            },
            displayEventEnd: true,
            eventClick: function (info) {
              $('#editmeeting #date_requested').val(info.event.extendedProps.date_requested);
              $('#editmeeting #topic').val(info.event.extendedProps.topic);
              $('#editmeeting #date_scheduled').val(info.event.extendedProps.date_scheduled);
              $('#editmeeting #time_start').val(info.event.extendedProps.time_start);
              $('#editmeeting #status_id').val(info.event.extendedProps.status_id);
              $('#editmeeting #host_id').val(info.event.extendedProps.host_id);
              $('#editmeeting #meetingid').val(info.event.extendedProps.meetingid);
              $('#editmeeting #passcode').val(info.event.extendedProps.passcode);
              $('#editmeeting #join_link').val(info.event.extendedProps.join_link);
              $('#editmeeting #start_link').val(info.event.extendedProps.start_link);
              $('#editmeeting #remarks').html(info.event.extendedProps.remarks);
              $('#editmeeting #id').val(info.event.extendedProps.id);
              $('#editModal').modal('show');
              $('.fc-popover-close').click();
            }
          });

          calendar.render();

          $.ajax({
            type: "GET",
            url: "assets/components/includes/fetch.php",
            data: {
              count_users: "",
              year: "<?= $year ?? date('Y') ?>",
            },
            dataType: "json",
            success: function (response) {
              $("#count_users").html(response.counts);
            },
          });
          $.ajax({
            type: "GET",
            url: "assets/components/includes/fetch.php",
            data: {
              count_meetings: "",
              year: "<?= $year ?? date('Y') ?>",
            },
            dataType: "json",
            success: function (response) {
              $("#count_meetings").html(response.counts);
            },
          });
          $.ajax({
            type: "GET",
            url: "assets/components/includes/fetch.php",
            data: {
              count_helpdesks: "",
              year: "<?= $year ?? date('Y') ?>",
            },
            dataType: "json",
            success: function (response) {
              $("#count_helpdesks").html(response.counts);
            },
          });
          $.ajax({
            type: "GET",
            url: "assets/components/includes/fetch.php",
            data: {
              count_equipment: "",
              year: "<?= $year ?? date('Y') ?>",
            },
            dataType: "json",
            success: function (response) {
              $("#count_equipment").html(response.counts);
            },
          });
          $.ajax({
            url: "assets/components/includes/charts.php?bar1",
            data: {
              year: "<?= $year ?? date('Y') ?>",
            },
            type: "GET",
            dataType: "json",
            success: function (data) {
              barChart(data);
            },
          });
          $.ajax({
            url: "assets/components/includes/charts.php?donut1",
            data: {
              year: "<?= $year ?? date('Y') ?>",
            },
            type: "GET",
            dataType: "json",
            success: function (data) {
              donutChart(data);
            },
          });
          $.ajax({
            url: "assets/components/includes/charts.php?pie1",
            data: {
              year: "<?= $year ?? date('Y') ?>",
            },
            type: "GET",
            dataType: "json",
            success: function (data) {
              pieChart(data);
            },
          });
          $.ajax({
            url: "assets/components/includes/charts.php?line1",
            data: {
              year: "<?= $year ?? date('Y') ?>",
            },
            type: "GET",
            dataType: "json",
            success: function (data) {
              lineChart(data);
            },
          });

          var calendarEl = $("#zoom-calendar")[0];

          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "dayGridMonth",
          });

          calendar.render();
        });

        function barChart(data) {
          var barChart = new ApexCharts(document.querySelector("#barChart"), {
            series: [{
              name: "Assistances",
              data: data.map(item => parseInt(item.count_helpdesks)),
            },],
            chart: {
              type: "bar",
              height: 350,
            },
            plotOptions: {
              bar: {
                distributed: true,
                horizontal: true,
                dataLabels: {
                  position: "bottom",
                },
              },
            },
            colors: [
              "#182015",
              "#0e1355",
              "#233b76",
              "#2e56b6",
              "#3961d7",
              "#457ced",
              "#5182f1",
              "#5c8dfa",
              "#6798ff",
              "#729ee3",
            ],
            dataLabels: {
              enabled: true,
              textAnchor: "start",
              style: {
                colors: ["#fff"],
              },
              formatter: function (val, opt) {
                return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val;
              },
              offsetX: 0,
              dropShadow: {
                enabled: true,
              },
            },
            stroke: {
              width: 1,
              colors: ["#fff"],
            },
            xaxis: {
              categories: data.map((item) => item.category),
            },
            yaxis: {
              labels: {
                show: false,
              },
            },
          }).render();
        }

        function donutChart(data) {
          var donutChart = new ApexCharts(document.querySelector("#donutChart"), {
            series: data.map(item => parseInt(item.count_helpdesks)),
            chart: {
              height: 350,
              type: "donut",
              toolbar: {
                show: true,
              },
            },
            colors: [
              "#182015",
              "#0e1355",
              "#233b76",
              "#2e56b6",
              "#3961d7",
              "#457ced",
              "#5182f1",
              "#5c8dfa",
              "#6798ff",
              "#729ee3",
            ],
            labels: data.map((item) => item.division),
          }).render();
        }

        function pieChart(data) {
          var pieChart = new ApexCharts(document.querySelector("#pieChart"), {
            series: data.map(item => parseInt(item.count_helpdesks)),
            chart: {
              height: 350,
              type: "pie",
              toolbar: {
                show: true,
              },
            },
            colors: [
              "#182015",
              "#0e1355",
              "#233b76",
              "#2e56b6",
              "#3961d7",
              "#457ced",
              "#5182f1",
              "#5c8dfa",
              "#6798ff",
              "#729ee3",
            ],
            labels: data.map((item) => item.sex),
          }).render();
        }

        function lineChart(data) {
          var lineChart = new ApexCharts(document.querySelector("#lineChart"), {
            series: [{
              name: "Assistances",
              data: data.map(item => parseInt(item.count_helpdesks)),
            },],
            chart: {
              height: 350,
              type: "line",
              zoom: {
                enabled: false,
              },
            },
            colors: [
              "#182015",
              "#0e1355",
              "#233b76",
              "#2e56b6",
              "#3961d7",
              "#457ced",
              "#5182f1",
              "#5c8dfa",
              "#6798ff",
              "#729ee3",
            ],
            dataLabels: {
              enabled: false,
            },
            stroke: {
              curve: "straight",
            },
            grid: {
              row: {
                colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                opacity: 0.5,
              },
            },
            xaxis: {
              categories: data.map((item) => item.months),
            },
          }).render();
        }
      </script>
      <?php
      break;
    case 'Employee';
      ?>
      <script>
        window.location.href = 'helpdesks.php';
      </script>
      <?php
      break;
    default:
      ?>
      <section class="section">
        <div class="row">
          <div class="col-lg-12">

            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Restricted Page</h5>
                <p>This page is restricted due to insufficient access.</p>
              </div>
            </div>

          </div>
        </div>
      </section>
    <?php
  }
  ?>
</main><!-- End #main -->
<?php require_once "assets/components/templates/footer.php"; ?>
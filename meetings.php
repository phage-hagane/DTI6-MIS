<?php $page = "Zoom Meetings" ?>
<?php $protected = true ?>
<?php require_once "assets/components/templates/header.php"; ?>
<?php require_once "assets/components/templates/topbar.php"; ?>
<?php require_once "assets/components/templates/sidebar.php"; ?>
<main id="main" class="main">
  <?php
  switch ($_SESSION['role']) {
    case 'Admin':
  ?>
      <section class="section">
        <div class="row">

          <div class="col-lg-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Zoom Request Form</h5>
                <form id="addmeeting" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>

                  <div class="mb-1">
                    <label for="date_requested">Date requested</label>
                    <input name="date_requested" type="date" class="form-control form-control-sm" id="date_requested" value="<?= date('Y-m-d') ?>" required />
                  </div>

                  <div class="mb-1">
                    <label for="requested_by">Requestor</label>
                    <select name="requested_by" class="form-select form-select-sm" id="requested_by">
                      <option value="" selected disabled>--</option>
                      <?php
                      $query = $conn->query("SELECT * FROM users WHERE role_id = 4 ORDER BY first_name ASC");
                      while ($row = $query->fetch_object()) {
                      ?>
                        <option value="<?= $row->id ?>">
                          <?= $row->first_name ?>
                          <?= $row->last_name ?>
                        </option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>

                  <div class="mb-1">
                    <label for="topic">Meeting topic or title</label>
                    <div class="text-end">
                      <textarea name="topic" class="form-control form-control-sm" id="topic" maxlength="150" required></textarea>
                      <div id="the-count">
                        <span id="current">0</span>
                        <span id="maximum">/ 150</span>
                      </div>

                      <script>
                        $(document).ready(function() {
                          $('#addmeeting #topic').on('keyup', function() {
                            var currentLength = $(this).val().length;
                            $('#addmeeting #current').text(currentLength);
                          });
                        });
                      </script>
                    </div>
                  </div>

                  <div class="mb-1">
                    <label for="date_scheduled">Date of schedule</label>
                    <input name="date_scheduled" type="date" class="form-control form-control-sm" id="date_scheduled" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required />
                  </div>

                  <div class="mb-1">
                    <label for="time_start">Time of schedule starts</label>
                    <input name="time_start" type="time" class="form-control form-control-sm" id="time_start" value="<?= date('H:i') ?>" required />
                  </div>

                  <div class="mb-1">
                    <label for="time_end">Time of schedule ends</label>
                    <input name="time_end" type="time" class="form-control form-control-sm" id="time_end" value="<?= date('H:i') ?>" required />
                  </div>

                  <a class="text-end btn-link" data-bs-toggle="collapse" href="#collapseExample2" id="ShowFields" onclick="ShowFields.style.display = 'none';HideFields.style.display = ''">
                    Show other fields
                  </a>
                  <a class="text-end btn-link" data-bs-toggle="collapse" href="#collapseExample2" id="HideFields" onclick="HideFields.style.display = 'none';ShowFields.style.display = ''" style="display: none;">
                    Hide other fields
                  </a>

                  <div class="collapse" id="collapseExample2">
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
                      <label for="remarks">Zoom Details</label>
                      <textarea name="remarks" class="form-control form-control-sm" id="remarks" rows="10"></textarea>
                    </div>

                    <div class="mb-1">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="send_email" name="send_email">
                        <label class="form-check-label" for="send_email">
                          Send Email
                        </label>
                      </div>
                    </div>
                  </div>
                  <div hidden>
                    <input name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
                    <input class="captcha-token" name="captcha-token" />
                    <input name="generated_by" id="generated_by" value="<?= $_SESSION['id'] ?>" />
                    <input name="request_meeting" />
                  </div>
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Zoom Request List</h5>
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
                        <input name="date_requested" type="date" class="form-control form-control-sm" id="date_requested" required />
                      </div>

                      <div class="mb-1">
                        <label for="requested_by">Requestor</label>
                        <select name="requested_by" class="form-select form-select-sm" id="requested_by">
                          <option value="" selected disabled>--</option>
                          <?php
                          $query = $conn->query("SELECT * FROM users WHERE role_id = 4 ORDER BY first_name ASC");
                          while ($row = $query->fetch_object()) {
                          ?>
                            <option value="<?= $row->id ?>">
                              <?= $row->first_name ?>
                              <?= $row->last_name ?>
                            </option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>

                      <div class="mb-1">
                        <label for="topic">Meeting topic or title</label>
                        <div class="text-end">
                          <textarea name="topic" class="form-control form-control-sm" id="topic" maxlength="150" required></textarea>
                        </div>
                      </div>

                      <div class="mb-1">
                        <label for="date_scheduled">Date of schedule</label>
                        <input name="date_scheduled" type="date" class="form-control form-control-sm" id="date_scheduled" min="<?= date('Y-m-d') ?>" required />
                      </div>

                      <div class="mb-1">
                        <label for="time_start">Time of schedule starts</label>
                        <input name="time_start" type="time" class="form-control form-control-sm" id="time_start" required />
                      </div>

                      <div class="mb-1">
                        <label for="time_end">Time of schedule ends</label>
                        <input name="time_end" type="time" class="form-control form-control-sm" id="time_end" required />
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
                        <label for="remarks">Zoom Details</label>
                        <textarea name="remarks" class="form-control form-control-sm" id="remarks" rows="11"></textarea>
                      </div>

                      <div class="mb-1">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="send_email" name="send_email">
                          <label class="form-check-label" for="send_email">
                            Send Email
                          </label>
                        </div>
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
          <script>
            jQuery(document).ready(function() {
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
                eventClick: function(info) {
                  $('#editmeeting #date_requested').val(info.event.extendedProps.date_requested);
                  $('#editmeeting #requested_by').val(info.event.extendedProps.requested_by);
                  $('#editmeeting #topic').html(info.event.extendedProps.topic);
                  $('#editmeeting #date_scheduled').val(info.event.extendedProps.date_scheduled);
                  $('#editmeeting #time_start').val(info.event.extendedProps.time_start);
                  $('#editmeeting #time_end').val(info.event.extendedProps.time_end);
                  $('#editmeeting #status_id').val(info.event.extendedProps.status_id);
                  $('#editmeeting #host_id').val(info.event.extendedProps.host_id);
                  $('#editmeeting #meetingid').val(info.event.extendedProps.meetingid);
                  $('#editmeeting #passcode').val(info.event.extendedProps.passcode);
                  $('#editmeeting #join_link').html(info.event.extendedProps.join_link);
                  $('#editmeeting #start_link').html(info.event.extendedProps.start_link);
                  $('#editmeeting #remarks').html(info.event.extendedProps.remarks);
                  $('#editmeeting #id').val(info.event.extendedProps.id);
                  $('#editModalLabel').html(info.event.extendedProps.meeting_number + '(<span class="text-' + info.event.extendedProps.color + '">' + info.event.extendedProps.status + '</span>)');
                  $('#editModal').modal('show');
                  $('.fc-popover-close').click();
                }
              });

              calendar.render();
            });
          </script>
        </div>
      </section>
    <?php
      break;
    case 'Employee':
    ?>
      <section class="section">
        <div class="row">

          <div class="col-lg-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Zoom Request Form</h5>
                <form id="addmeeting" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>

                  <div class="mb-1">
                    <label for="date_requested">Date requested</label>
                    <input name="date_requested" type="date" class="form-control form-control-sm" id="date_requested" value="<?= date('Y-m-d') ?>" required />
                  </div>

                  <div class="mb-1">
                    <label for="topic">Meeting topic or title</label>
                    <div class="text-end">
                      <textarea name="topic" class="form-control form-control-sm" id="topic" maxlength="150" required></textarea>
                      <div id="the-count">
                        <span id="current">0</span>
                        <span id="maximum">/ 150</span>
                      </div>

                      <script>
                        $(document).ready(function() {
                          $('#addmeeting #topic').on('keyup', function() {
                            var currentLength = $(this).val().length;
                            $('#addmeeting #current').text(currentLength);
                          });
                        });
                      </script>
                    </div>
                  </div>

                  <div class="mb-1">
                    <label for="date_scheduled">Date of schedule</label>
                    <input name="date_scheduled" type="date" class="form-control form-control-sm" id="date_scheduled" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required />
                  </div>

                  <div class="mb-1">
                    <label for="time_start">Time of schedule starts</label>
                    <input name="time_start" type="time" class="form-control form-control-sm" id="time_start" value="<?= date('H:i') ?>" required />
                  </div>

                  <div class="mb-1">
                    <label for="time_end">Time of schedule ends</label>
                    <input name="time_end" type="time" class="form-control form-control-sm" id="time_end" value="<?= date('H:i') ?>" required />
                  </div>

                  <div hidden>
                    <input name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
                    <input class="captcha-token" name="captcha-token" />
                    <input name="requested_by" id="requested_by" value="<?= $_SESSION['id'] ?>" />
                    <input name="status_id" id="status_id" value="1" />
                    <input name="request_meeting" />
                  </div>
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Zoom Request List</h5>
                <div id='zoom-calendar'></div>
              </div>
            </div>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="viewModalLabel"></h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="user-edit">

                    <form id="editmeeting" class="row g-3 needs-validation" enctype="multipart/form-data" novalidate>

                      <div class="mb-1">
                        <label for="date_requested">Date Requested</label>
                        <input name="date_requested" type="date" class="form-control form-control-sm" id="date_requested" disabled />
                      </div>

                      <div class="mb-1">
                        <label for="requested_by">Requestor</label>
                        <select name="requested_by" class="form-select form-select-sm" id="requested_by" disabled>
                          <option value="" selected disabled>--</option>
                          <?php
                          $query = $conn->query("SELECT * FROM users WHERE role_id = 4 ORDER BY first_name ASC");
                          while ($row = $query->fetch_object()) {
                          ?>
                            <option value="<?= $row->id ?>">
                              <?= $row->first_name ?>
                              <?= $row->last_name ?>
                            </option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>

                      <div class="mb-1">
                        <label for="topic">Meeting topic or title</label>
                        <div class="text-end">
                          <textarea name="topic" class="form-control form-control-sm" id="topic" maxlength="150" disabled></textarea>
                        </div>
                      </div>

                      <div class="mb-1">
                        <label for="date_scheduled">Date of schedule</label>
                        <input name="date_scheduled" type="date" class="form-control form-control-sm" id="date_scheduled" min="<?= date('Y-m-d') ?>" disabled />
                      </div>

                      <div class="mb-1">
                        <label for="time_start">Time of schedule starts</label>
                        <input name="time_start" type="time" class="form-control form-control-sm" id="time_start" disabled />
                      </div>

                      <div class="mb-1">
                        <label for="time_end">Time of schedule ends</label>
                        <input name="time_end" type="time" class="form-control form-control-sm" id="time_end" disabled />
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
                </div>
              </div>
            </div>
          </div>
          <script>
            jQuery(document).ready(function() {
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
                eventClick: function(info) {
                  $('#editmeeting #date_requested').val(info.event.extendedProps.date_requested);
                  $('#editmeeting #requested_by').val(info.event.extendedProps.requested_by);
                  $('#editmeeting #topic').html(info.event.extendedProps.topic);
                  $('#editmeeting #date_scheduled').val(info.event.extendedProps.date_scheduled);
                  $('#editmeeting #time_start').val(info.event.extendedProps.time_start);
                  $('#editmeeting #time_end').val(info.event.extendedProps.time_end);
                  $('#editmeeting #status_id').val(info.event.extendedProps.status_id);
                  $('#editmeeting #host_id').val(info.event.extendedProps.host_id);
                  $('#editmeeting #meetingid').val(info.event.extendedProps.meetingid);
                  $('#editmeeting #passcode').val(info.event.extendedProps.passcode);
                  $('#editmeeting #join_link').html(info.event.extendedProps.join_link);
                  $('#editmeeting #start_link').html(info.event.extendedProps.start_link);
                  $('#editmeeting #remarks').html(info.event.extendedProps.remarks);
                  $('#editmeeting #id').val(info.event.extendedProps.id);
                  $('#viewModalLabel').html(info.event.extendedProps.meeting_number + '(<span class="text-' + info.event.extendedProps.color + '">' + info.event.extendedProps.status + '</span>)');
                  $('#viewModal').modal('show');
                  $('.fc-popover-close').click();
                }
              });

              calendar.render();
            });
          </script>
        </div>
      </section>
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
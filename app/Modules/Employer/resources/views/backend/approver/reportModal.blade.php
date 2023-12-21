<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Reporting -> {{ $candidate->firstname}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="POST" id="submit-form" action="{{ route('employer.approver.company.candidate.candidateReportSubmit',$candidate->id)}}">
              <label>Message</label>
              <input type="hidden" name="attendanceid" value="{{ $attendanceid}}">
              <input type="hidden" name="companyid" value="{{ $companyid}}">

              <textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
              @error('remarks')
                    <div class="text-danger">{{ $message}}</div>
              @enderror 
              <div class="modal-footer">
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
          </form>
      </div>
    </div>
  </div>
{{--
    Shared admin order popups: Change Order Status & Update Delivery Charge.
    Required vars: $order_statuses, $payment_statuses, $shipping_methods, $delivery_pay_by
    Trigger buttons must carry data-* attributes (see openOrderStatusModal / openDeliveryChargeModal).
--}}
<style>
    .ord-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 50px;
        white-space: nowrap;
        border: 1px solid transparent;
    }
    .ord-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .ord-st-pending           { background: #fef3c7; color: #b45309; border-color: #fde68a; }
    .ord-st-confirmed         { background: #e0e7ff; color: #4338ca; border-color: #c7d2fe; }
    .ord-st-processing        { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
    .ord-st-handed_to_courier { background: #f3e8ff; color: #7e22ce; border-color: #e9d5ff; }
    .ord-st-on_the_way        { background: #cffafe; color: #0e7490; border-color: #a5f3fc; }
    .ord-st-delivered         { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .ord-st-cancelled         { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
    .ord-st-returned          { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }

    .pay-st-unpaid         { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
    .pay-st-paid           { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .pay-st-partially_paid { background: #fef3c7; color: #b45309; border-color: #fde68a; }
    .pay-st-failed         { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .pay-st-refunded       { background: #e0e7ff; color: #4338ca; border-color: #c7d2fe; }

    .ord-modal .modal-content { border: 0; border-radius: 16px; overflow: hidden; }
    .ord-modal .modal-header { background: #f8fafc; border-bottom: 1px solid #eef2f6; padding: 16px 22px; }
    .ord-modal .modal-title { font-weight: 800; font-size: 16px; }
    .ord-modal .modal-body { padding: 20px 22px; }
    .ord-modal .form-label { font-weight: 700; font-size: 13px; color: #334155; margin-bottom: 5px; }
    .ord-modal .form-control, .ord-modal .form-select { border-radius: 10px; min-height: 42px; }
    .ord-modal-summary {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 13px;
        margin-bottom: 16px;
    }
    .ord-modal-summary .row-line { display: flex; justify-content: space-between; padding: 3px 0; }
    .ord-modal-summary .row-line.total { border-top: 1px solid #e2e8f0; margin-top: 6px; padding-top: 8px; font-weight: 800; font-size: 14.5px; color: #0f172a; }
</style>

<!-- Change Order Status Modal -->
<div class="modal fade ord-modal" id="orderStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="orderStatusForm">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="ri-refresh-line me-1"></i> Change Order Status &mdash; <span id="osmOrderNumber" class="text-primary"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="osmOrderId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Status <span class="text-danger">*</span></label>
                            <select name="order_status" id="osmOrderStatus" class="form-select" required>
                                @foreach($order_statuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" id="osmPaymentStatus" class="form-select">
                                @foreach($payment_statuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Courier / Shipping Method</label>
                            <select name="shipping_method" id="osmShippingMethod" class="form-select">
                                <option value="">-- Not Assigned --</option>
                                @foreach($shipping_methods as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" id="osmTrackingNumber" class="form-control" placeholder="Courier tracking ID">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Admin Note</label>
                            <textarea name="admin_note" id="osmAdminNote" class="form-control" rows="3" placeholder="Internal note (not visible to customer)"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="osmSubmitBtn"><i class="ri-save-line me-1"></i> Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Delivery Charge Modal -->
<div class="modal fade ord-modal" id="deliveryChargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deliveryChargeForm">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="ri-truck-line me-1"></i> Delivery Charge &mdash; <span id="dcmOrderNumber" class="text-primary"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="dcmOrderId">

                    <div class="ord-modal-summary">
                        <div class="row-line"><span>Subtotal</span><strong>BDT <span id="dcmSubtotal">0.00</span></strong></div>
                        <div class="row-line"><span>Discount</span><strong>- BDT <span id="dcmDiscount">0.00</span></strong></div>
                        <div class="row-line"><span>Delivery Charge</span><strong>+ BDT <span id="dcmChargePreview">0.00</span></strong></div>
                        <div class="row-line total"><span>New Total</span><span>BDT <span id="dcmTotalPreview">0.00</span></span></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Delivery Charge (BDT) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="delivery_charge" id="dcmDeliveryCharge" class="form-control" required>
                            <div class="d-flex flex-wrap gap-1 mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDeliveryPreset(60)">Inside Dhaka 60</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDeliveryPreset(120)">Outside 120</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setDeliveryPreset(0)">Free</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Charge Paid By</label>
                            <select name="delivery_charge_pay_by" id="dcmPayBy" class="form-select">
                                <option value="">-- Not Paid Yet --</option>
                                @foreach($delivery_pay_by as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Reference / Transaction No.</label>
                            <input type="text" name="delivery_charge_reference_number" id="dcmReference" class="form-control" placeholder="e.g. bKash TrxID">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="dcmSubmitBtn"><i class="ri-save-line me-1"></i> Save Delivery Charge</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Order Processing Percentage & Benefit Modal -->
<div class="modal fade ord-modal" id="orderProcessingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="orderProcessingForm">
                <div class="modal-header">
                    <div>
                        <h6 class="modal-title mb-0">
                            <i class="ri-percent-line me-1 text-primary"></i> Order Processing Commission &mdash; <span id="opmOrderNumber" class="text-primary"></span>
                        </h6>
                        <small class="text-muted">Calculates processing percentage based on product benefit amount</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="opmOrderId">

                    <!-- Loading Spinner -->
                    <div id="opmLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="text-muted mt-2">Loading product benefit &amp; commission details...</div>
                    </div>

                    <!-- Main Form Content -->
                    <div id="opmContent" style="display: none;">
                        <!-- Status Alert Banner -->
                        <div id="opmAlertBanner" class="alert d-flex align-items-center gap-2 mb-3" style="border-radius: 10px; font-size: 13.5px;">
                            <i id="opmAlertIcon" class="ri-information-line fs-5"></i>
                            <div id="opmAlertText"></div>
                        </div>

                        <!-- 3 Summary Metrics -->
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="p-2 border rounded bg-light">
                                    <div class="text-muted small">Total Product Profit (Benefit)</div>
                                    <div class="fw-bold fs-6 text-dark">BDT <span id="opmTotalOrderBenefit">0.00</span></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-2 border rounded bg-light">
                                    <div class="text-muted small">Eligible Benefit for Commission</div>
                                    <div class="fw-bold fs-6 text-primary">BDT <span id="opmTotalEligibleBenefit">0.00</span></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-2 border rounded bg-light" style="background: #ecfdf5 !important; border-color: #a7f3d0 !important;">
                                    <div class="text-muted small">Calculated Commission Amount</div>
                                    <div class="fw-bold fs-6 text-success">BDT <span id="opmProcessingAmountPreview">0.00</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="row g-3 mb-3">
                            <!-- Order Processing By -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">Order Processed By</label>
                                    @if(Auth::check())
                                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size: 11px;" onclick="assignProcessorToMe({{ Auth::id() }})">
                                            Assign to Me
                                        </button>
                                    @endif
                                </div>
                                <select name="order_processing_by" id="opmProcessingBy" class="form-select">
                                    <option value="">-- Select Order Processor --</option>
                                    @if(!empty($users))
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role ?: 'staff') }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="small text-muted mt-1">If processor is product owner, commission will be 0%.</div>
                            </div>

                            <!-- Processing Percentage -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">
                                        Commission Percentage (%) <span class="text-danger">*</span>
                                    </label>
                                    <span id="opmPermissionBadge" class="badge bg-secondary-transparent text-secondary" style="font-size: 10.5px;"></span>
                                </div>
                                <div class="input-group">
                                    <input type="number" step="0.1" min="0" max="100" name="processing_percentage" id="opmPercentage" class="form-control" value="25" required>
                                    <span class="input-group-text">%</span>
                                </div>

                                <!-- Preset buttons (visible when user can edit) -->
                                <div id="opmPresets" class="d-flex flex-wrap gap-1 mt-1">
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" onclick="setPercentagePreset(10)">10%</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" onclick="setPercentagePreset(15)">15%</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" onclick="setPercentagePreset(20)">20%</button>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 fw-bold" onclick="setPercentagePreset(25)">Default 25%</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" onclick="setPercentagePreset(30)">30%</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" onclick="setPercentagePreset(35)">35%</button>
                                </div>
                                <div id="opmPermissionNote" class="small text-muted mt-1"></div>
                            </div>

                            <!-- Commission Payment Status -->
                            <div class="col-md-6">
                                <label class="form-label">Commission Paid to Processor?</label>
                                <select name="is_paid_processing_amount" id="opmIsPaid" class="form-select">
                                    <option value="No">No &mdash; Unpaid</option>
                                    <option value="Yes">Yes &mdash; Paid</option>
                                </select>
                            </div>

                            <!-- Live Calculation Formula Box -->
                            <div class="col-md-6">
                                <label class="form-label">Calculation Formula</label>
                                <div class="p-2 border rounded bg-white text-muted small d-flex align-items-center justify-content-between">
                                    <span>BDT <strong id="opmFormulaBenefit" class="text-dark">0.00</strong> &times; <strong id="opmFormulaPct" class="text-primary">25%</strong></span>
                                    <span>= <strong id="opmFormulaResult" class="text-success fs-6">BDT 0.00</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Product & Benefit Breakdown Table -->
                        <div class="border rounded p-2 mb-2 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                                <span class="fw-bold small text-dark"><i class="ri-file-list-3-line me-1"></i> Product Benefit Breakdown</span>
                                <span class="badge bg-white text-muted border">Formula: (Selling &minus; Purchase) &times; Qty</span>
                            </div>
                            <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                                <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 12px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Owner</th>
                                            <th class="text-end">Selling Price</th>
                                            <th class="text-end">Purchase Price</th>
                                            <th class="text-end">Unit Profit</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Total Profit</th>
                                            <th class="text-center">Commission Eligibility</th>
                                        </tr>
                                    </thead>
                                    <tbody id="opmItemsTableBody">
                                        <!-- Populated dynamically via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="opmSubmitBtn">
                        <i class="ri-save-line me-1"></i> Save Processing Commission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        var STATUS_URL = "{{ url('order-status-update') }}";
        var DELIVERY_URL = "{{ url('order-delivery-charge-update') }}";
        var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function notify(message, ok) {
            if (window.iziToast) {
                iziToast.show({
                    title: ok ? 'Success' : 'Error',
                    message: message,
                    position: 'topRight',
                    timeout: 5000,
                    color: ok ? 'green' : 'red',
                    close: true,
                });
            } else {
                alert(message);
            }
        }

        function getModal(id) {
            return bootstrap.Modal.getOrCreateInstance(document.getElementById(id));
        }

        function postForm(url, form, submitBtn) {
            var originalHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

            return fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: new FormData(form),
            })
            .then(function (res) {
                return res.json().then(function (json) { return { ok: res.ok, json: json }; });
            })
            .then(function (r) {
                if (!r.ok) {
                    var msg = r.json.message || 'Validation failed';
                    if (r.json.errors) {
                        msg = Object.values(r.json.errors).map(function (e) { return e[0]; }).join('<br>');
                    }
                    throw new Error(msg);
                }
                if (!r.json.result || r.json.result.key !== 200) {
                    throw new Error((r.json.result && r.json.result.val) || 'Something went wrong');
                }
                return r.json.result;
            })
            .finally(function () {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            });
        }

        function swapClass(el, prefix, value) {
            el.className = el.className.split(' ').filter(function (c) { return c.indexOf(prefix) !== 0; }).join(' ');
            el.classList.add(prefix + value);
        }

        // ---------- Order Status ----------
        window.openOrderStatusModal = function (btn) {
            var d = btn.dataset;
            document.getElementById('osmOrderId').value = d.orderId;
            document.getElementById('osmOrderNumber').textContent = '#' + (d.orderNumber || d.orderId);
            document.getElementById('osmOrderStatus').value = d.orderStatus || 'pending';
            document.getElementById('osmPaymentStatus').value = d.paymentStatus || 'unpaid';
            document.getElementById('osmShippingMethod').value = d.shippingMethod || '';
            document.getElementById('osmTrackingNumber').value = d.trackingNumber || '';
            document.getElementById('osmAdminNote').value = d.adminNote || '';
            getModal('orderStatusModal').show();
        };

        document.getElementById('orderStatusForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = this;
            var orderId = document.getElementById('osmOrderId').value;

            postForm(STATUS_URL, form, document.getElementById('osmSubmitBtn'))
                .then(function (result) {
                    document.querySelectorAll('[data-status-badge="' + orderId + '"]').forEach(function (el) {
                        swapClass(el, 'ord-st-', result.order_status);
                        el.textContent = result.order_status_label;
                    });
                    document.querySelectorAll('[data-payment-badge="' + orderId + '"]').forEach(function (el) {
                        swapClass(el, 'pay-st-', result.payment_status);
                        el.textContent = result.payment_status_label;
                    });
                    document.querySelectorAll('[data-shipping-text="' + orderId + '"]').forEach(function (el) {
                        var sel = document.getElementById('osmShippingMethod');
                        el.textContent = sel.value ? sel.options[sel.selectedIndex].text : 'Not Assigned';
                    });
                    document.querySelectorAll('[data-tracking-text="' + orderId + '"]').forEach(function (el) {
                        el.textContent = document.getElementById('osmTrackingNumber').value || '—';
                    });
                    document.querySelectorAll('[data-admin-note-text="' + orderId + '"]').forEach(function (el) {
                        el.textContent = document.getElementById('osmAdminNote').value || 'No admin note yet.';
                    });
                    // keep trigger buttons in sync for the next open
                    document.querySelectorAll('[data-status-trigger="' + orderId + '"]').forEach(function (b) {
                        b.dataset.orderStatus = result.order_status;
                        b.dataset.paymentStatus = result.payment_status;
                        b.dataset.shippingMethod = document.getElementById('osmShippingMethod').value;
                        b.dataset.trackingNumber = document.getElementById('osmTrackingNumber').value;
                        b.dataset.adminNote = document.getElementById('osmAdminNote').value;
                    });
                    getModal('orderStatusModal').hide();
                    notify(result.val, true);
                })
                .catch(function (err) { notify(err.message, false); });
        });

        // ---------- Delivery Charge ----------
        function num(v) { var n = parseFloat(v); return isNaN(n) ? 0 : n; }
        function fmt(n) { return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }

        function refreshDeliveryPreview() {
            var form = document.getElementById('deliveryChargeForm');
            var subtotal = num(form.dataset.subtotal);
            var discount = num(form.dataset.discount);
            var charge = num(document.getElementById('dcmDeliveryCharge').value);
            document.getElementById('dcmChargePreview').textContent = fmt(charge);
            document.getElementById('dcmTotalPreview').textContent = fmt(Math.max(0, subtotal - discount + charge));
        }

        window.setDeliveryPreset = function (amount) {
            document.getElementById('dcmDeliveryCharge').value = amount;
            refreshDeliveryPreview();
        };

        window.openDeliveryChargeModal = function (btn) {
            var d = btn.dataset;
            var form = document.getElementById('deliveryChargeForm');
            form.dataset.subtotal = d.subtotal || 0;
            form.dataset.discount = d.discount || 0;
            document.getElementById('dcmOrderId').value = d.orderId;
            document.getElementById('dcmOrderNumber').textContent = '#' + (d.orderNumber || d.orderId);
            document.getElementById('dcmSubtotal').textContent = fmt(num(d.subtotal));
            document.getElementById('dcmDiscount').textContent = fmt(num(d.discount));
            document.getElementById('dcmDeliveryCharge').value = num(d.deliveryCharge);
            document.getElementById('dcmPayBy').value = d.payBy || '';
            document.getElementById('dcmReference').value = d.reference || '';
            refreshDeliveryPreview();
            getModal('deliveryChargeModal').show();
        };

        document.getElementById('dcmDeliveryCharge').addEventListener('input', refreshDeliveryPreview);

        document.getElementById('deliveryChargeForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = this;
            var orderId = document.getElementById('dcmOrderId').value;

            postForm(DELIVERY_URL, form, document.getElementById('dcmSubmitBtn'))
                .then(function (result) {
                    document.querySelectorAll('[data-delivery-text="' + orderId + '"]').forEach(function (el) {
                        el.textContent = result.delivery_charge;
                    });
                    document.querySelectorAll('[data-total-text="' + orderId + '"]').forEach(function (el) {
                        el.textContent = result.total;
                    });
                    document.querySelectorAll('[data-delivery-payby-text="' + orderId + '"]').forEach(function (el) {
                        var sel = document.getElementById('dcmPayBy');
                        el.textContent = sel.value ? sel.options[sel.selectedIndex].text : 'Not Paid Yet';
                    });
                    document.querySelectorAll('[data-delivery-ref-text="' + orderId + '"]').forEach(function (el) {
                        el.textContent = document.getElementById('dcmReference').value || '—';
                    });
                    document.querySelectorAll('[data-delivery-trigger="' + orderId + '"]').forEach(function (b) {
                        b.dataset.deliveryCharge = document.getElementById('dcmDeliveryCharge').value;
                        b.dataset.payBy = document.getElementById('dcmPayBy').value;
                        b.dataset.reference = document.getElementById('dcmReference').value;
                    });
                    getModal('deliveryChargeModal').hide();
                    notify(result.val, true);
                })
                .catch(function (err) { notify(err.message, false); });
        });

        // ---------- Order Processing Commission ----------
        var PROCESSING_INFO_URL = "{{ url('order-processing-info') }}";
        var PROCESSING_UPDATE_URL = "{{ url('order-processing-update') }}";
        var currentOrderProcData = null;

        window.openOrderProcessingModal = function (orderId) {
            var modal = getModal('orderProcessingModal');
            document.getElementById('opmOrderId').value = orderId;
            document.getElementById('opmLoading').style.display = 'block';
            document.getElementById('opmContent').style.display = 'none';
            modal.show();

            fetch(PROCESSING_INFO_URL + '/' + orderId, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            })
            .then(function (res) { return res.json(); })
            .then(function (res) {
                if (!res.result || res.result.key !== 200) {
                    throw new Error((res.result && res.result.val) || 'Could not load order processing info');
                }
                currentOrderProcData = res.result.data;
                renderOrderProcessingModal(currentOrderProcData);
            })
            .catch(function (err) {
                notify(err.message, false);
                modal.hide();
            });
        };

        function renderOrderProcessingModal(data) {
            document.getElementById('opmOrderNumber').textContent = '#' + data.order_number;
            document.getElementById('opmLoading').style.display = 'none';
            document.getElementById('opmContent').style.display = 'block';

            var procSelect = document.getElementById('opmProcessingBy');
            procSelect.value = data.order_processing_by || '';

            var pctInput = document.getElementById('opmPercentage');
            pctInput.value = data.processing_percentage !== null ? data.processing_percentage : 25;

            var isPaidSelect = document.getElementById('opmIsPaid');
            isPaidSelect.value = data.is_paid_processing_amount || 'No';

            // Check permissions:
            // "it need to create pop up product owner can give percentage. but default 25%"
            // "only product owner change the percentage. if other people processing the product sell then he can get percentage autometic"
            var canEdit = data.can_edit_percentage;
            var permBadge = document.getElementById('opmPermissionBadge');
            var permNote = document.getElementById('opmPermissionNote');
            var presets = document.getElementById('opmPresets');

            if (canEdit) {
                pctInput.removeAttribute('readonly');
                pctInput.removeAttribute('disabled');
                permBadge.className = 'badge bg-success-transparent text-success';
                permBadge.textContent = data.is_product_owner ? 'Product Owner (Can Edit %)' : 'Admin (Can Edit %)';
                permNote.textContent = 'As the Product Owner / Admin, you can set the commission percentage (default 25%).';
                presets.style.display = 'flex';
            } else {
                pctInput.setAttribute('readonly', 'readonly');
                permBadge.className = 'badge bg-secondary-transparent text-secondary';
                permBadge.innerHTML = '<i class="ri-lock-line me-1"></i> Locked';
                permNote.textContent = 'Only the Product Owner can change this commission percentage. Your commission is calculated automatically.';
                presets.style.display = 'none';
            }

            recalcModalCommission();
        }

        window.assignProcessorToMe = function (userId) {
            document.getElementById('opmProcessingBy').value = userId;
            recalcModalCommission();
        };

        window.setPercentagePreset = function (pct) {
            var pctInput = document.getElementById('opmPercentage');
            if (!pctInput.hasAttribute('readonly')) {
                pctInput.value = pct;
                recalcModalCommission();
            }
        };

        function recalcModalCommission() {
            if (!currentOrderProcData) return;

            var selectedProcessorId = parseInt(document.getElementById('opmProcessingBy').value, 10) || null;
            var pct = parseFloat(document.getElementById('opmPercentage').value) || 0;

            var items = currentOrderProcData.items || [];
            var totalOrderBenefit = 0;
            var totalEligibleBenefit = 0;
            var hasOwnerMatch = false;

            var tbody = document.getElementById('opmItemsTableBody');
            tbody.innerHTML = '';

            items.forEach(function (item) {
                var sPrice = parseFloat(item.selling_price) || 0;
                var pPrice = parseFloat(item.purchase_price) || 0;
                var qty = parseInt(item.quantity) || 1;
                var uBenefit = Math.max(0, sPrice - pPrice);
                var tBenefit = uBenefit * qty;
                totalOrderBenefit += tBenefit;

                var ownerId = item.product_owner_id ? parseInt(item.product_owner_id, 10) : null;
                var isSameOwner = (selectedProcessorId && ownerId && selectedProcessorId === ownerId);

                var eligible = isSameOwner ? 0 : tBenefit;
                totalEligibleBenefit += eligible;

                if (isSameOwner) {
                    hasOwnerMatch = true;
                }

                var tr = document.createElement('tr');
                tr.innerHTML = 
                    '<td>' +
                        '<div class="d-flex align-items-center gap-2">' +
                            '<img src="' + item.product_image + '" style="width: 28px; height: 28px; border-radius: 4px; object-fit: cover; border: 1px solid #e2e8f0;">' +
                            '<div><strong>' + item.product_name + '</strong></div>' +
                        '</div>' +
                    '</td>' +
                    '<td><span class="badge bg-light text-dark">' + item.product_owner_name + '</span></td>' +
                    '<td class="text-end">BDT ' + fmt(sPrice) + '</td>' +
                    '<td class="text-end text-muted">BDT ' + fmt(pPrice) + '</td>' +
                    '<td class="text-end fw-bold text-dark">BDT ' + fmt(uBenefit) + '</td>' +
                    '<td class="text-center">' + qty + '</td>' +
                    '<td class="text-end fw-bold text-dark">BDT ' + fmt(tBenefit) + '</td>' +
                    '<td class="text-center">' +
                        (isSameOwner
                            ? '<span class="badge bg-warning-transparent text-warning fw-bold"><i class="ri-forbid-line me-1"></i> Owner Processing (0%)</span>'
                            : '<span class="badge bg-success-transparent text-success fw-bold"><i class="ri-check-line me-1"></i> Eligible (' + pct + '%)</span>'
                        ) +
                    '</td>';
                tbody.appendChild(tr);
            });

            var commissionAmount = Math.round((totalEligibleBenefit * (pct / 100)) * 100) / 100;

            document.getElementById('opmTotalOrderBenefit').textContent = fmt(totalOrderBenefit);
            document.getElementById('opmTotalEligibleBenefit').textContent = fmt(totalEligibleBenefit);
            document.getElementById('opmProcessingAmountPreview').textContent = fmt(commissionAmount);

            document.getElementById('opmFormulaBenefit').textContent = fmt(totalEligibleBenefit);
            document.getElementById('opmFormulaPct').textContent = pct + '%';
            document.getElementById('opmFormulaResult').textContent = 'BDT ' + fmt(commissionAmount);

            var banner = document.getElementById('opmAlertBanner');
            var bannerText = document.getElementById('opmAlertText');
            var bannerIcon = document.getElementById('opmAlertIcon');

            if (!selectedProcessorId) {
                banner.className = 'alert alert-secondary d-flex align-items-center gap-2 mb-3';
                bannerIcon.className = 'ri-user-line fs-5';
                bannerText.innerHTML = '<strong>Processor Unassigned:</strong> Select who is processing this order to automatically calculate their commission amount.';
            } else if (hasOwnerMatch && totalEligibleBenefit === 0) {
                banner.className = 'alert alert-warning d-flex align-items-center gap-2 mb-3';
                bannerIcon.className = 'ri-error-warning-line fs-5';
                bannerText.innerHTML = '<strong>Product Owner is Processing:</strong> As requested, when <code>order_processing_by == product_owner</code>, no percentage commission is calculated (<strong>BDT 0.00</strong>).';
            } else if (hasOwnerMatch && totalEligibleBenefit > 0) {
                banner.className = 'alert alert-info d-flex align-items-center gap-2 mb-3';
                bannerIcon.className = 'ri-information-line fs-5';
                bannerText.innerHTML = '<strong>Mixed Order:</strong> Some items are owned by the processor (0% commission) and other items are eligible for <strong>' + pct + '%</strong> commission (BDT ' + fmt(commissionAmount) + ').';
            } else {
                banner.className = 'alert alert-success d-flex align-items-center gap-2 mb-3';
                bannerIcon.className = 'ri-checkbox-circle-line fs-5';
                bannerText.innerHTML = '<strong>Automatic Calculation:</strong> Processor is selling for the product owner. Automatic commission at <strong>' + pct + '%</strong> of benefit = <strong>BDT ' + fmt(commissionAmount) + '</strong>.';
            }
        }

        document.getElementById('opmProcessingBy').addEventListener('change', recalcModalCommission);
        document.getElementById('opmPercentage').addEventListener('input', recalcModalCommission);

        document.getElementById('orderProcessingForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = this;
            var orderId = document.getElementById('opmOrderId').value;

            postForm(PROCESSING_UPDATE_URL, form, document.getElementById('opmSubmitBtn'))
                .then(function (result) {
                    // Update in Order List
                    document.querySelectorAll('[data-proc-amount="' + orderId + '"]').forEach(function (el) {
                        el.textContent = 'BDT ' + result.processing_amount;
                    });
                    document.querySelectorAll('[data-proc-pct="' + orderId + '"]').forEach(function (el) {
                        el.textContent = result.processing_percentage + '%';
                    });
                    document.querySelectorAll('[data-proc-name="' + orderId + '"]').forEach(function (el) {
                        el.textContent = result.processor_name;
                    });
                    document.querySelectorAll('[data-proc-paid="' + orderId + '"]').forEach(function (el) {
                        var isPaid = (result.is_paid_processing_amount === 'Yes');
                        el.textContent = isPaid ? 'Paid' : 'Unpaid';
                        el.className = 'badge ' + (isPaid ? 'bg-success' : 'bg-secondary');
                    });

                    // Update in Order Details page if present
                    document.querySelectorAll('[data-od-proc-amount]').forEach(function (el) {
                        el.textContent = result.processing_amount;
                    });
                    document.querySelectorAll('[data-od-proc-pct]').forEach(function (el) {
                        el.textContent = result.processing_percentage + '%';
                    });
                    document.querySelectorAll('[data-od-proc-name]').forEach(function (el) {
                        el.textContent = result.processor_name;
                    });
                    document.querySelectorAll('[data-od-proc-paid]').forEach(function (el) {
                        var isPaid = (result.is_paid_processing_amount === 'Yes');
                        el.textContent = isPaid ? 'Paid' : 'Unpaid';
                        el.className = 'badge ' + (isPaid ? 'bg-success-transparent text-success' : 'bg-warning-transparent text-warning');
                    });

                    getModal('orderProcessingModal').hide();
                    notify(result.val, true);
                })
                .catch(function (err) { notify(err.message, false); });
        });
    })();
</script>


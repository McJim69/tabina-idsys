<!-- Jitsi External API Script -->
<script src="https://meet.ffmuc.net/external_api.js"></script>

<!-- Jitsi Call Overlay Modal -->
<div id="jitsiCallOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.95); z-index: 99999; justify-content: center; align-items: center; flex-direction: column; font-family: sans-serif; box-sizing: border-box;">
	<!-- Overlay Controls Header -->
	<div style="width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; background: #111; color: #fff; box-sizing: border-box; border-bottom: 1px solid #222;">
		<div style="display: flex; align-items: center;">
			<i class="fas fa-phone-volume" style="margin-right: 8px; color: #28a745; font-size: 18px;"></i>
			<span class="font-weight-bold" id="jitsiCallTitle" style="font-size: 14px; font-weight: bold; color: #fff;">LGU Tabina Call Room</span>
		</div>
		<div style="display: flex; align-items: center;">
			<button id="minimizeJitsiBtn" onclick="toggleMinimizeJitsiCall()" style="background: #333; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 13px; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
				<i class="fas fa-compress-alt" style="margin-right: 6px;"></i> Minimize
			</button>
			<button onclick="hangupJitsiCall()" style="background: #dc3545; color: #fff; border: none; padding: 6px 15px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 13px; display: flex; align-items: center; justify-content: center;">
				<i class="fas fa-phone-slash" style="margin-right: 6px;"></i> End Call
			</button>
		</div>
	</div>
	
	<!-- Iframe Container -->
	<div id="jitsiIframeContainer" style="width: 100%; flex-grow: 1; background: #000; position: relative; height: calc(100vh - 55px); box-sizing: border-box;">
		<div id="jitsiLoading" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; color: #fff; background: #141414; z-index: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; box-sizing: border-box;">
			<i class="fas fa-circle-notch fa-spin" style="font-size: 40px; margin-bottom: 15px; color: #007bff;"></i>
			<h6 style="font-weight: bold; margin: 0 0 5px 0; font-size: 14px; color: #fff;">Connecting to WebRTC media servers...</h6>
			<small style="color: #6c757d; font-size: 11px;">Please allow camera and microphone access when prompted</small>
		</div>
	</div>
</div>

<!-- Incoming Call Overlay Banner -->
<div id="incomingCallOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(10, 10, 10, 0.85); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 100000; justify-content: center; align-items: center; box-sizing: border-box; font-family: sans-serif;">
	<div style="background: #18191a; border: 1px solid #2f3031; color: #fff; width: 340px; border-radius: 24px; padding: 25px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); text-align: center; position: relative; box-sizing: border-box; display: flex; flex-direction: column; align-items: center;">
		<!-- Close Button (X) -->
		<button onclick="declineCall()" style="position: absolute; top: 15px; right: 15px; width: 32px; height: 32px; border-radius: 50%; background: #2a2b2c; border: none; color: #ccc; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.background='#3a3b3c'" onmouseout="this.style.background='#2a2b2c'">
			<i class="fas fa-times"></i>
		</button>
		
		<!-- McJim Branding -->
		<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
			<img src="images/mcjimlogo.png" style="height: 24px;" alt="McJim Logo">
			<span style="font-size: 11px; font-weight: bold; color: #38bdf8; letter-spacing: 1px; text-transform: uppercase;">Incoming Call</span>
		</div>
		
		<!-- Dynamic Caller Avatar -->
		<img id="incomingCallAvatar" src="images/users/blank.jpg" style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid #38bdf8; object-fit: cover; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);" alt="Avatar">
		
		<!-- Caller Display Label -->
		<h6 id="incomingCallSender" style="margin: 0 0 5px 0; font-size: 21px; font-weight: bold; color: #fff; line-height: 1.25;">Ciso James</h6>
		<p style="margin: 0 0 5px 0; font-size: 15px; color: #bbb; line-height: 1.25;">is calling you</p>
		
		<!-- Subtext Hint -->
		<p style="margin: 10px 0 25px 0; font-size: 13px; color: #888; line-height: 1.4;">Establish a secure video/audio connection via McJim Server.</p>
		
		<!-- Circular Call Action Buttons -->
		<div style="display: flex; gap: 40px; justify-content: center; width: 100%;">
			<!-- Decline Option -->
			<div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
				<button onclick="declineCall()" style="width: 56px; height: 56px; border-radius: 50%; background: #dc2626; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 15px rgba(220,38,38,0.4); transition: transform 0.15s, background 0.2s;" onmouseover="this.style.transform='scale(1.08)'; this.style.background='#b91c1c';" onmouseout="this.style.transform='scale(1)'; this.style.background='#dc2626';">
					<i class="fas fa-times"></i>
				</button>
				<span style="font-size: 12px; color: #888; font-weight: 500;">Decline</span>
			</div>
			<!-- Accept Option -->
			<div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
				<button onclick="acceptCall()" style="width: 56px; height: 56px; border-radius: 50%; background: #16a34a; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 15px rgba(22,163,74,0.4); transition: transform 0.15s, background 0.2s;" onmouseover="this.style.transform='scale(1.08)'; this.style.background='#15803d';" onmouseout="this.style.transform='scale(1)'; this.style.background='#16a34a';">
					<i class="fas fa-phone"></i>
				</button>
				<span style="font-size: 12px; color: #888; font-weight: 500;">Accept</span>
			</div>
		</div>
	</div>
</div>

<!-- Outbound Calling Overlay Banner -->
<div id="outboundCallOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: radial-gradient(circle at center, #1e1e24 0%, #101012 100%); z-index: 100000; justify-content: center; align-items: center; box-sizing: border-box; font-family: sans-serif;">
	<div style="background: transparent; color: #fff; width: 340px; text-align: center; position: relative; box-sizing: border-box; display: flex; flex-direction: column; align-items: center;">
		
		<!-- McJim Branding -->
		<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 30px;">
			<img src="images/mcjimlogo.png" style="height: 32px;" alt="McJim Logo">
		</div>

		<!-- Dynamic Receiver Avatar -->
		<img id="outboundCallAvatar" src="images/users/blank.jpg" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #38bdf8; object-fit: cover; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);" alt="Avatar">
		
		<!-- Receiver Display Label -->
		<h6 id="outboundCallReceiver" style="margin: 0 0 10px 0; font-size: 24px; font-weight: bold; color: #fff; line-height: 1.25;">Ciso James</h6>
		
		<!-- Status text -->
		<p id="outboundCallStatus" style="margin: 0 0 40px 0; font-size: 15px; color: #38bdf8; font-weight: 500; letter-spacing: 0.5px;">Calling...</p>
		
		<!-- Calling State Button Container -->
		<div id="outboundCallingActions" style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
			<button onclick="hangupJitsiCall()" style="width: 56px; height: 56px; border-radius: 50%; background: #dc2626; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 15px rgba(220,38,38,0.4); transition: transform 0.15s, background 0.2s;" onmouseover="this.style.transform='scale(1.08)'; this.style.background='#b91c1c';" onmouseout="this.style.transform='scale(1)'; this.style.background='#dc2626';">
				<i class="fas fa-phone-slash"></i>
			</button>
			<span style="font-size: 12px; color: #888; font-weight: 500;">End Call</span>
		</div>
		
		<!-- No Answer State Button Container -->
		<div id="outboundNoAnswerActions" style="display: none; gap: 40px; justify-content: center; width: 100%;">
			<!-- Redial Option -->
			<div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
				<button id="redialBtn" style="width: 56px; height: 56px; border-radius: 50%; background: #16a34a; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 15px rgba(22,163,74,0.4); transition: transform 0.15s, background 0.2s;" onmouseover="this.style.transform='scale(1.08)'; this.style.background='#15803d';" onmouseout="this.style.transform='scale(1)'; this.style.background='#16a34a';">
					<i class="fas fa-phone"></i>
				</button>
				<span style="font-size: 12px; color: #888; font-weight: 500;">Redial</span>
			</div>
			<!-- Close Option -->
			<div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
				<button onclick="hangupJitsiCall()" style="width: 56px; height: 56px; border-radius: 50%; background: #2a2b2c; border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: transform 0.15s, background 0.2s;" onmouseover="this.style.transform='scale(1.08)'; this.style.background='#3a3b3c';" onmouseout="this.style.transform='scale(1)'; this.style.background='#2a2b2c';">
					<i class="fas fa-times"></i>
				</button>
				<span style="font-size: 12px; color: #888; font-weight: 500;">Close</span>
			</div>
		</div>
	</div>
</div>

<style>
@keyframes ringPulse {
	0% {
		transform: scale(0.9);
		opacity: 1;
	}
	100% {
		transform: scale(1.4);
		opacity: 0;
	}
}
@keyframes outboundPulse {
	0% {
		transform: scale(0.9);
		opacity: 1;
	}
	100% {
		transform: scale(1.4);
		opacity: 0;
	}
}
</style>

<!-- Floating Ongoing Call Banner -->
<div id="ongoingCallBanner" style="display: none; position: fixed; top: 70px; left: 50%; transform: translateX(-50%); background: rgba(40, 167, 69, 0.95); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: #fff; padding: 10px 20px; border-radius: 30px; z-index: 9998; box-shadow: 0 4px 15px rgba(40,167,69,0.3); align-items: center; gap: 12px; font-family: sans-serif; font-size: 13.5px; font-weight: bold; border: 1px solid rgba(255,255,255,0.2);">
	<i class="fas fa-phone-volume" style="font-size: 14px; animation: callIconPulse 1.2s infinite ease-in-out;"></i>
	<span>Ongoing Call in progress...</span>
	<button id="ongoingCallJoinBtn" onclick="" style="background: #fff; color: #28a745; border: none; padding: 5px 15px; border-radius: 20px; font-weight: bold; cursor: pointer; font-size: 11.5px; box-shadow: 0 2px 5px rgba(0,0,0,0.15); transition: transform 0.15s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">Join Call</button>
</div>

<style>
@keyframes callIconPulse {
	0% {
		transform: scale(1);
		opacity: 1;
	}
	50% {
		transform: scale(1.2);
		opacity: 0.7;
	}
	100% {
		transform: scale(1);
		opacity: 1;
	}
}
</style>

<script type="text/javascript">
	var globalUsername = "<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'LGU Member'; ?>";
	var activeJitsiAPI = null;

	function joinJitsiCall(roomName, callType, targetName, targetAvatar, windowRef) {
		stopRingSound();
		stopOutboundRingSound();
		
		// Mark this room as processed so it won't ring again in this session
		sessionStorage.setItem('processed_call_' + roomName, 'true');
		
		// Reset/hide Jitsi overlays if any
		var overlay = document.getElementById('jitsiCallOverlay');
		if (overlay) overlay.style.display = 'none';
		
		var outboundOverlay = document.getElementById('outboundCallOverlay');
		if (outboundOverlay) outboundOverlay.style.display = 'none';
		
		var incomingOverlay = document.getElementById('incomingCallOverlay');
		if (incomingOverlay) incomingOverlay.style.display = 'none';

		// Generate direct Jitsi URL on the secure Jitsi server with branding configs
		var isVideoMuted = (callType === 'audio') ? 'true' : 'false';
		var displayName = typeof globalUsername !== 'undefined' ? globalUsername : "LGU Member";
		var jitsiUrl = "https://meet.ffmuc.net/" + roomName 
			+ "#config.prejoinPageEnabled=false"
			+ "&config.prejoinConfig.enabled=false"
			+ "&config.disableDeepLinking=true"
			+ "&config.startWithAudioMuted=false"
			+ "&config.startWithVideoMuted=" + isVideoMuted
			+ "&config.defaultLocalDisplayName=" + encodeURIComponent(displayName)
			+ "&userInfo.displayName=" + encodeURIComponent('"' + displayName + '"')
			+ "&config.APP_NAME=" + encodeURIComponent("McJim Call")
			+ "&interfaceConfig.APP_NAME=" + encodeURIComponent("McJim Call")
			+ "&interfaceConfig.SHOW_JITSI_WATERMARK=false";
		
		// Open call in a new browser tab/window to grant WebRTC camera/microphone access in secure origin
		if (windowRef && !windowRef.closed) {
			windowRef.location.href = jitsiUrl;
		} else {
			var callWindow = window.open(jitsiUrl, '_blank');
			if (!callWindow) {
				// If popup blocker prevents opening, fallback to inline redirect or alert
				alert("Popup blocker active! Please allow popups or click this link to join: " + jitsiUrl);
				window.location.href = jitsiUrl;
			}
		}
	}
	
	function hangupJitsiCall() {
		stopRingSound();
		stopOutboundRingSound();
		
		// Suppress ongoing banner for this room so it doesn't pop back up immediately
		if (activeJitsiAPI && activeJitsiAPI.roomName) {
			suppressOngoingCall(activeJitsiAPI.roomName);
		} else if (currentIncomingRoom) {
			suppressOngoingCall(currentIncomingRoom);
		} else {
			var callButtons = document.querySelectorAll('button[onclick*="joinJitsiCall"]');
			if (callButtons.length > 0) {
				var latestBtn = callButtons[callButtons.length - 1];
				var match = latestBtn.getAttribute('onclick').match(/joinJitsiCall\("(.+?)",\s*"(.+?)"\)/);
				if (match) {
					suppressOngoingCall(match[1]);
				}
			}
		}
		
		var outOverlay = document.getElementById('outboundCallOverlay');
		if (outOverlay) {
			outOverlay.style.display = 'none';
		}
		var overlay = document.getElementById('jitsiCallOverlay');
		if (overlay) {
			overlay.style.display = 'none';
		}
		if (activeJitsiAPI) {
			activeJitsiAPI.dispose();
			activeJitsiAPI = null;
		}
		// Clear container of any non-loader elements
		var container = document.getElementById('jitsiIframeContainer');
		if (container) {
			var children = container.children;
			for (var i = children.length - 1; i >= 0; i--) {
				if (children[i].id !== 'jitsiLoading') {
					children[i].remove();
				}
			}
		}
		// Refresh ongoing calls banner to hide it immediately
		var ongoingBanner = document.getElementById('ongoingCallBanner');
		if (ongoingBanner) {
			ongoingBanner.style.display = 'none';
		}
	}

	// Web Audio Message Notification Chime (Ding)
	function playMessageNotificationSound() {
		try {
			var AudioCtx = window.AudioContext || window.webkitAudioContext;
			if (!AudioCtx) return;
			
			var ctx = new AudioCtx();
			var osc = ctx.createOscillator();
			var gain = ctx.createGain();
			
			osc.type = 'sine';
			
			// A clean, pleasant high-frequency chime/ding
			// Quick ramp-up, then gradual exponential decay
			osc.frequency.setValueAtTime(880, ctx.currentTime); // A5 note
			osc.frequency.exponentialRampToValueAtTime(1320, ctx.currentTime + 0.08); // E6 note
			
			gain.gain.setValueAtTime(0.001, ctx.currentTime);
			gain.gain.linearRampToValueAtTime(0.2, ctx.currentTime + 0.03);
			gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.45);
			
			osc.connect(gain);
			gain.connect(ctx.destination);
			
			osc.start();
			osc.stop(ctx.currentTime + 0.5);
		} catch(e) {
			console.error("Failed to play message sound:", e);
		}
	}

	// Web Audio Telephone Ringing Synthesizer
	var ringAudioContext = null;
	var ringIntervalId = null;
	var ringTimeoutId = null;

	function playRingSound() {
		stopRingSound(); // Ensure no parallel ringing
		
		try {
			var AudioCtx = window.AudioContext || window.webkitAudioContext;
			if (!AudioCtx) return;
			
			ringAudioContext = new AudioCtx();
			
			var playSingleRing = function() {
				if (!ringAudioContext) return;
				if (ringAudioContext.state === 'suspended') {
					ringAudioContext.resume();
				}
				
				var osc1 = ringAudioContext.createOscillator();
				var osc2 = ringAudioContext.createOscillator();
				var lfo = ringAudioContext.createOscillator();
				var lfoGain = ringAudioContext.createGain();
				var mainGain = ringAudioContext.createGain();
				
				osc1.type = 'sine';
				osc1.frequency.value = 453; // Hz
				
				osc2.type = 'sine';
				osc2.frequency.value = 440; // Hz
				
				lfo.type = 'sine';
				lfo.frequency.value = 18; // Hz (Vibrato/warble frequency)
				lfoGain.gain.value = 15; // frequency warble depth
				
				lfo.connect(lfoGain);
				lfoGain.connect(osc1.frequency);
				lfoGain.connect(osc2.frequency);
				
				mainGain.gain.setValueAtTime(0, ringAudioContext.currentTime);
				mainGain.gain.linearRampToValueAtTime(0.25, ringAudioContext.currentTime + 0.1);
				mainGain.gain.setValueAtTime(0.25, ringAudioContext.currentTime + 1.2);
				mainGain.gain.exponentialRampToValueAtTime(0.001, ringAudioContext.currentTime + 1.5);
				
				osc1.connect(mainGain);
				osc2.connect(mainGain);
				mainGain.connect(ringAudioContext.destination);
				
				osc1.start();
				osc2.start();
				lfo.start();
				
				osc1.stop(ringAudioContext.currentTime + 1.5);
				osc2.stop(ringAudioContext.currentTime + 1.5);
				lfo.stop(ringAudioContext.currentTime + 1.5);
			};
			
			playSingleRing();
			ringIntervalId = setInterval(playSingleRing, 3000);
			
			// Automatically stop ringing after 30 seconds
			ringTimeoutId = setTimeout(function() {
				stopRingSound();
			}, 30000);
			
		} catch(e) {
			console.error("Failed to initialize Web Audio Ring:", e);
		}
	}

	function stopRingSound() {
		if (ringIntervalId) {
			clearInterval(ringIntervalId);
			ringIntervalId = null;
		}
		if (ringTimeoutId) {
			clearTimeout(ringTimeoutId);
			ringTimeoutId = null;
		}
		if (ringAudioContext) {
			try {
				ringAudioContext.close();
			} catch(e) {}
			ringAudioContext = null;
		}
	}

	// Incoming Call UI handlers
	var currentIncomingRoom = null;
	var currentIncomingType = null;
	var currentIncomingMsgId = null;

	function showIncomingCallOverlay(callerName, roomName, callType, avatarUrl, msgId) {
		// Prevent repeat ringing if call has already been answered, declined, or dismissed in this browser session
		if (msgId && sessionStorage.getItem('processed_call_msg_' + msgId) === 'true') {
			return;
		}
		if (sessionStorage.getItem('processed_call_' + roomName) === 'true') {
			return;
		}

		currentIncomingRoom = roomName;
		currentIncomingType = callType;
		currentIncomingMsgId = msgId;
		
		var overlay = document.getElementById('incomingCallOverlay');
		var senderLabel = document.getElementById('incomingCallSender');
		var avatarImg = document.getElementById('incomingCallAvatar');
		
		if (!overlay) return;
		
		if (senderLabel) senderLabel.innerText = callerName;
		if (avatarImg) {
			avatarImg.src = avatarUrl || 'images/users/blank.jpg';
		}
		
		overlay.style.display = 'flex';
		
		if (typeof playRingSound === 'function') {
			playRingSound();
		}
		
		// If unanswered in 30 seconds, automatically hide overlay
		setTimeout(function() {
			if (overlay.style.display === 'flex' && currentIncomingRoom === roomName) {
				declineCall();
			}
		}, 30000);
	}
	
	function acceptCall() {
		var overlay = document.getElementById('incomingCallOverlay');
		if (overlay) overlay.style.display = 'none';
		
		if (currentIncomingRoom && currentIncomingType) {
			if (currentIncomingMsgId) {
				sessionStorage.setItem('processed_call_msg_' + currentIncomingMsgId, 'true');
			}
			sessionStorage.setItem('processed_call_' + currentIncomingRoom, 'true');
			joinJitsiCall(currentIncomingRoom, currentIncomingType);
		}
		currentIncomingRoom = null;
		currentIncomingType = null;
		currentIncomingMsgId = null;
	}
	
	function declineCall() {
		var overlay = document.getElementById('incomingCallOverlay');
		if (overlay) overlay.style.display = 'none';
		
		if (currentIncomingRoom) {
			if (currentIncomingMsgId) {
				sessionStorage.setItem('processed_call_msg_' + currentIncomingMsgId, 'true');
			}
			sessionStorage.setItem('processed_call_' + currentIncomingRoom, 'true');
		}
		stopRingSound();
		currentIncomingRoom = null;
		currentIncomingType = null;
		currentIncomingMsgId = null;
	}

	// Outbound Ringing Audio Synthesizer
	var outboundAudioContext = null;
	var outboundIntervalId = null;
	var outboundTimeoutId = null;

	function playOutboundRingSound() {
		stopOutboundRingSound();
		
		try {
			var AudioCtx = window.AudioContext || window.webkitAudioContext;
			if (!AudioCtx) return;
			
			outboundAudioContext = new AudioCtx();
			
			var playSingleOutboundRing = function() {
				if (!outboundAudioContext) return;
				if (outboundAudioContext.state === 'suspended') {
					outboundAudioContext.resume();
				}
				
				var osc1 = outboundAudioContext.createOscillator();
				var osc2 = outboundAudioContext.createOscillator();
				var mainGain = outboundAudioContext.createGain();
				
				osc1.type = 'sine';
				osc1.frequency.value = 400; // Hz
				
				osc2.type = 'sine';
				osc2.frequency.value = 450; // Hz
				
				mainGain.gain.setValueAtTime(0, outboundAudioContext.currentTime);
				mainGain.gain.linearRampToValueAtTime(0.12, outboundAudioContext.currentTime + 0.1);
				mainGain.gain.setValueAtTime(0.12, outboundAudioContext.currentTime + 1.0);
				mainGain.gain.exponentialRampToValueAtTime(0.001, outboundAudioContext.currentTime + 1.2);
				
				osc1.connect(mainGain);
				osc2.connect(mainGain);
				mainGain.connect(outboundAudioContext.destination);
				
				osc1.start();
				osc2.start();
				
				osc1.stop(outboundAudioContext.currentTime + 1.2);
				osc2.stop(outboundAudioContext.currentTime + 1.2);
			};
			
			playSingleOutboundRing();
			outboundIntervalId = setInterval(playSingleOutboundRing, 4000); // ring 1.2s, pause 2.8s
			
			// Timeout calling after 45 seconds
			outboundTimeoutId = setTimeout(function() {
				showNoAnswerScreen();
			}, 45000);
			
		} catch(e) {
			console.error("Outbound ring tone error:", e);
		}
	}

	function stopOutboundRingSound() {
		if (outboundIntervalId) {
			clearInterval(outboundIntervalId);
			outboundIntervalId = null;
		}
		if (outboundTimeoutId) {
			clearTimeout(outboundTimeoutId);
			outboundTimeoutId = null;
		}
		if (outboundAudioContext) {
			try {
				outboundAudioContext.close();
			} catch(e) {}
			outboundAudioContext = null;
		}
	}

	var lastOutboundRoom = null;
	var lastOutboundType = null;
	var lastOutboundTarget = null;
	var lastOutboundAvatar = null;

	function showOutboundCallOverlay(receiverName, roomName, callType, avatarUrl) {
		lastOutboundRoom = roomName;
		lastOutboundType = callType;
		lastOutboundTarget = receiverName;
		lastOutboundAvatar = avatarUrl;

		var overlay = document.getElementById('outboundCallOverlay');
		var receiverLabel = document.getElementById('outboundCallReceiver');
		var statusText = document.getElementById('outboundCallStatus');
		var avatarImg = document.getElementById('outboundCallAvatar');
		
		if (!overlay) return;
		
		if (receiverLabel) receiverLabel.innerText = receiverName;
		if (statusText) statusText.innerText = "Calling...";
		if (avatarImg) {
			avatarImg.src = avatarUrl || 'images/users/blank.jpg';
		}
		
		// Reset buttons to calling state
		var callActions = document.getElementById('outboundCallingActions');
		var noAnswerActions = document.getElementById('outboundNoAnswerActions');
		if (callActions) callActions.style.display = 'flex';
		if (noAnswerActions) noAnswerActions.style.display = 'none';
		
		overlay.style.display = 'flex';
		playOutboundRingSound();
	}

	function showNoAnswerScreen() {
		stopOutboundRingSound();
		
		// Close active Jitsi overlay connection if loaded in background
		var jitsiOverlay = document.getElementById('jitsiCallOverlay');
		if (jitsiOverlay) jitsiOverlay.style.display = 'none';
		if (activeJitsiAPI) {
			activeJitsiAPI.dispose();
			activeJitsiAPI = null;
		}
		
		var statusText = document.getElementById('outboundCallStatus');
		if (statusText) statusText.innerText = "No answer";
		
		var callActions = document.getElementById('outboundCallingActions');
		var noAnswerActions = document.getElementById('outboundNoAnswerActions');
		if (callActions) callActions.style.display = 'none';
		if (noAnswerActions) noAnswerActions.style.display = 'flex';
		
		// Bind Redial click action
		var redial = document.getElementById('redialBtn');
		if (redial) {
			redial.onclick = function() {
				if (lastOutboundRoom && lastOutboundType) {
					joinJitsiCall(lastOutboundRoom, lastOutboundType, lastOutboundTarget, lastOutboundAvatar);
				}
			};
		}
	}

	// Ongoing call checking & suppression helpers
	var suppressedRooms = {};

	function suppressOngoingCall(roomName) {
		suppressedRooms[roomName] = Date.now();
	}

	function checkOngoingCalls() {
		var activeCallOverlay = document.getElementById('jitsiCallOverlay');
		var ongoingBanner = document.getElementById('ongoingCallBanner');
		var incomingOverlay = document.getElementById('incomingCallOverlay');
		var outboundOverlay = document.getElementById('outboundCallOverlay');
		
		if (!ongoingBanner) return;
		
		// If the user is currently in a call, hide the floating banner
		if (activeCallOverlay && activeCallOverlay.style.display === 'flex') {
			ongoingBanner.style.display = 'none';
			return;
		}
		
		// If there is an incoming or outbound call screen active, hide the floating banner
		if ((incomingOverlay && incomingOverlay.style.display === 'flex') || 
			(outboundOverlay && outboundOverlay.style.display === 'flex')) {
			ongoingBanner.style.display = 'none';
			return;
		}
		
		// Look for any join call buttons inside the chat message container
		var callButtons = document.querySelectorAll('button[onclick*="joinJitsiCall"]');
		if (callButtons.length > 0) {
			// Find the latest call button
			var latestBtn = callButtons[callButtons.length - 1];
			var onclickAttr = latestBtn.getAttribute('onclick');
			var match = onclickAttr.match(/joinJitsiCall\("(.+?)",\s*"(.+?)"\)/);
			if (match) {
				var roomName = match[1];
				var callType = match[2];
				
				// If room is suppressed (ended in the last 5 minutes), do not show banner
				if (suppressedRooms[roomName] && (Date.now() - suppressedRooms[roomName] < 300000)) {
					ongoingBanner.style.display = 'none';
					return;
				}
				
				ongoingBanner.style.display = 'flex';
				
				var joinBtn = document.getElementById('ongoingCallJoinBtn');
				if (joinBtn) {
					joinBtn.onclick = function() {
						joinJitsiCall(roomName, callType);
					};
				}
				return;
			}
		}
		
		ongoingBanner.style.display = 'none';
	}

	// Periodically monitor ongoing calls every 2 seconds
	setInterval(checkOngoingCalls, 2000);

	// Minimize / Maximize window manager for active call modal
	var isJitsiMinimized = false;

	function toggleMinimizeJitsiCall() {
		var overlay = document.getElementById('jitsiCallOverlay');
		var container = document.getElementById('jitsiIframeContainer');
		var title = document.getElementById('jitsiCallTitle');
		var minBtn = document.getElementById('minimizeJitsiBtn');
		
		if (!overlay) return;
		
		if (!isJitsiMinimized) {
			// Minimize!
			overlay.style.width = '280px';
			overlay.style.height = '220px';
			overlay.style.top = 'auto';
			overlay.style.left = 'auto';
			overlay.style.bottom = '20px';
			overlay.style.right = '20px';
			overlay.style.borderRadius = '16px';
			overlay.style.border = '2px solid #28a745';
			overlay.style.boxShadow = '0 10px 40px rgba(0,0,0,0.6)';
			
			if (container) {
				container.style.height = 'calc(100% - 45px)';
			}
			
			if (title) {
				title.style.display = 'none';
			}
			
			if (minBtn) {
				minBtn.innerHTML = '<i class="fas fa-expand-alt"></i>';
				minBtn.title = "Maximize Call";
			}
			
			isJitsiMinimized = true;
		} else {
			// Restore/Maximize!
			overlay.style.width = '100vw';
			overlay.style.height = '100vh';
			overlay.style.top = '0';
			overlay.style.left = '0';
			overlay.style.bottom = 'auto';
			overlay.style.right = 'auto';
			overlay.style.borderRadius = '0';
			overlay.style.border = 'none';
			overlay.style.boxShadow = 'none';
			
			if (container) {
				container.style.height = 'calc(100% - 55px)';
			}
			
			if (title) {
				title.style.display = 'inline';
			}
			
			if (minBtn) {
				minBtn.innerHTML = '<i class="fas fa-compress-alt" style="margin-right: 6px;"></i> Minimize';
				minBtn.title = "Minimize Call";
			}
			
			isJitsiMinimized = false;
		}
	}

	// Trigger incoming call modal on page load if call was sent recently
	document.addEventListener('DOMContentLoaded', function() {
		setTimeout(function() {
			var recentCallRow = document.querySelector('[data-incoming-call-recent="true"]');
			if (recentCallRow) {
				var msgId = recentCallRow.getAttribute('data-msg-id');
				if (msgId && sessionStorage.getItem('processed_call_msg_' + msgId) === 'true') {
					return;
				}
				
				var joinBtn = recentCallRow.querySelector('button[onclick*="joinJitsiCall"]');
				if (joinBtn && typeof showIncomingCallOverlay === 'function') {
					var senderEl = recentCallRow.querySelector('.text-muted.font-weight-bold');
					var senderName = senderEl ? senderEl.textContent.trim().split(' ')[0] : 'LGU Member';
					
					var headerAvatarEl = document.querySelector('.messenger-header img, img[src*="images/users/"]');
					var senderAvatar = headerAvatarEl ? headerAvatarEl.getAttribute('src') : 'images/users/blank.jpg';
					
					var match = joinBtn.getAttribute('onclick').match(/joinJitsiCall\("(.+?)",\s*"(.+?)"\)/);
					if (match) {
						var roomName = match[1];
						var callType = match[2];
						showIncomingCallOverlay(senderName, roomName, callType, senderAvatar, msgId);
					}
				}
			}
		}, 1000); // 1s delay to ensure script contexts are initialized and DOM is ready
	});

	// Responsive Mobile Visual Viewport Chat Adjuster
	(function() {
		function adjustChatViewport() {
			var wrapper = document.querySelector('.container-fluid-chat-wrapper') || document.querySelector('.messenger-container');
			if (!wrapper) return;

			var viewport = window.visualViewport;
			var isMobile = window.innerWidth < 768;

			if (!viewport || !isMobile) {
				// Reset styles on desktop or if visualViewport is not supported
				wrapper.style.removeProperty('position');
				wrapper.style.removeProperty('top');
				wrapper.style.removeProperty('left');
				wrapper.style.removeProperty('right');
				wrapper.style.removeProperty('bottom');
				wrapper.style.removeProperty('height');
				wrapper.style.removeProperty('margin-top');
				wrapper.style.removeProperty('transform');
				
				var innerWin = wrapper.querySelector('.messenger-window') || wrapper.querySelector('.messenger-container');
				if (innerWin) {
					innerWin.style.removeProperty('height');
					innerWin.style.removeProperty('min-height');
					innerWin.style.removeProperty('margin-top');
					innerWin.style.removeProperty('margin-bottom');
				}
				return;
			}

			var headerHeight = 50; // top navigation bar height on mobile
			var vvHeight = viewport.height;
			
			// Only allow visual viewport offset if an input/editable element is focused
			var activeEl = document.activeElement;
			var isInputFocused = activeEl && (activeEl.matches('.chat-input-area, .chat-input, input, textarea') || activeEl.getAttribute('contenteditable') === 'true');
			var vvOffsetTop = isInputFocused ? viewport.offsetTop : 0;

			// Apply fixed positioning to visual viewport bounds
			wrapper.style.setProperty('position', 'fixed', 'important');
			wrapper.style.setProperty('left', '0', 'important');
			wrapper.style.setProperty('right', '0', 'important');
			wrapper.style.setProperty('bottom', '0', 'important');
			wrapper.style.setProperty('margin-top', '0', 'important');
			wrapper.style.setProperty('height', (vvHeight - headerHeight) + 'px', 'important');
			wrapper.style.setProperty('top', (headerHeight + vvOffsetTop) + 'px', 'important');

			// Force inner window/container to take 100% height of the adjusted wrapper and remove top/bottom margins
			var innerWin = wrapper.querySelector('.messenger-window') || wrapper.querySelector('.messenger-container');
			if (innerWin) {
				innerWin.style.setProperty('height', '100%', 'important');
				innerWin.style.setProperty('min-height', '0', 'important');
				innerWin.style.setProperty('margin-top', '0', 'important');
				innerWin.style.setProperty('margin-bottom', '0', 'important');
			}

			// Scroll active feed to bottom
			var feed = wrapper.querySelector('.chat-feed-container') || wrapper.querySelector('.messenger-feed') || wrapper.querySelector('#chat-messages-scroll-pane');
			if (feed) {
				feed.scrollTop = feed.scrollHeight;
			}
		}

		if (window.visualViewport) {
			window.visualViewport.addEventListener('resize', adjustChatViewport);
			window.visualViewport.addEventListener('scroll', adjustChatViewport);
			
			// Listen to focus events to ensure layout recalculates when keyboard opens/closes
			document.addEventListener('focusin', function(e) {
				if (e.target && (e.target.matches('.chat-input-area, .chat-input, input, textarea') || e.target.getAttribute('contenteditable') === 'true')) {
					setTimeout(adjustChatViewport, 150);
					setTimeout(adjustChatViewport, 300); // multiple attempts to handle slow keyboard animations
				}
			});

			document.addEventListener('focusout', function(e) {
				if (e.target && (e.target.matches('.chat-input-area, .chat-input, input, textarea') || e.target.getAttribute('contenteditable') === 'true')) {
					setTimeout(function() {
						window.scrollTo(0, 0);
						adjustChatViewport();
					}, 150);
				}
			});

			// Run on DOM loaded and load events
			document.addEventListener('DOMContentLoaded', adjustChatViewport);
			window.addEventListener('load', adjustChatViewport);
			
			// Periodic check to ensure layout is aligned
			setInterval(adjustChatViewport, 1000);
		}
	})();
</script>
var jQuery_1_11_1 = jQuery_1_11_1 || $.noConflict();
(function ($, window, undefined) {
	function SSCountdown(opts) {
		if (!(this instanceof SSCountdown)) {
			return new SSCountdown(opts);
		}	
		this.options = {};
		this.total   = null;
		this.days    = null;
		this.hours   = 1;
		this.minutes = null;
		this.seconds = null;
		this.intervalId = null;
		
		this.timezone_key = [];
		this.timezone_value = [];
		
		this.init(opts);
	}
	SSCountdown.prototype = {
		init: function (opts) {
			var self = this;
			
			self.$container = $(".ss-countdown-container");
			
			self.$container.on("click", ".ssCountdownSubscribeSelector", function(e){
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var $this = $(this),
					$form = $this.closest('form'),
					$emailAddress = $form.find("input[name='email_address']"),
					$errorContainer = $form.find(".ss-error-container");
				$errorContainer.css({"color": "#FF0000"}).html("").hide();
				if($emailAddress.val() == "") {
					$errorContainer.html("Vui lòng điền địa chỉ email của bạn!").fadeIn();
				}
				else {
					var re = /([0-9a-zA-Z\.\-\_]+)@([0-9a-zA-Z\.\-\_]+)\.([0-9a-zA-Z\.\-\_]+)/,
						email_address = $emailAddress.val();
					if(email_address.match(re) == null) {
						$errorContainer.html("Email không đúng!").fadeIn();
					}
					else {
						$.ajax({
							type: "POST",
							data: {
								email: email_address
							},
							url: "subscribe/subscribe.php",
							success: function(){
								$emailAddress.val("");
								$errorContainer.css({"color": "#39F"}).html("Cảm ơn bạn đã đăng ký!").fadeIn();
								setTimeout(function(){
									$errorContainer.css({"color": "#FF0000"}).html("").hide();
								}, 3000);
							}
						});
					}
				}
				
				return false;
			});
			
			if(typeof opts.now == "undefined") {
				if (!Date.now) {
				    Date.now = function() { return new Date().getTime(); };
				}
				opts.now = (Date.now() / 1000);
			}
			if(typeof opts.startDate == "undefined") {
				opts.startDate = opts.now;
			}
			if(typeof opts.endDate == "undefined") {
				/*Expect PHP Timestamp*/
				var GMT = opts.timezone.replace(/GMT/, ""),
					date_string = opts.date + "T" + opts.time + GMT,
					endDate = new Date(date_string);
				/* console.log(endDate); */
				opts.endDate = (endDate.getTime() / 1000);
			}
									
			for (var attr in opts) {
				if (opts.hasOwnProperty(attr)) {
					self.options[attr] = opts[attr];
				}
			}
			
			if (self.options.now >= self.options.endDate) {
		        return;
		    }
			
			self.total   = Math.floor((opts.endDate - opts.startDate)/86400);
			self.days    = Math.floor((opts.endDate - opts.now ) / 86400);
			self.hours   = 24 - Math.floor(((opts.endDate - opts.now) % 86400) / 3600);
			self.minutes = 60 - Math.floor((((opts.endDate - opts.now) % 86400) % 3600) / 60) ;
			self.seconds = 60 - Math.floor((opts.endDate - opts.now) % 86400 % 3600 % 60);
			
		    self.setSeconds();
		    self.setMinutes();
		    self.setHours();
		    self.setDays();
		    
			self.start();
		},
		start: function()
		{
			var  self = this;
			
			if (self.options.now >= self.options.endDate) {
		        return;
		    }
			
			self.intervalId = setInterval(function(){
                if ( self.seconds > 59 ) {
                    if (60 - self.minutes == 0 && 24 - self.hours == 0 && self.days == 0) {
                        clearInterval(self.intervalId);
                        return;
                    }
                    self.seconds = 1;
                    if (self.minutes > 59) {
                    	self.minutes = 1;
                        self.setMinutes();
                        if (self.hours > 23) {
                        	self.hours = 1;
                            if (self.days > 0) {
                            	self.days--;
                                self.setDays();
                            }
                        } else {
                        	self.hours++;
                        }
                        self.setHours();
                    } else {
                    	self.minutes++;
                    }
                    self.setMinutes();
                } else {
                	self.seconds++;
                }
                self.setSeconds();
            }, 1000);
		},
		setDays: function(){
			var self = this;
			
            var cdays = $(".ss-days-canvas").get(0);
            var ctx = cdays.getContext("2d");
            ctx.clearRect(0, 0, cdays.width, cdays.height);
            ctx.beginPath();
            ctx.strokeStyle = self.options.daysColor;
            
            ctx.shadowBlur    = 10;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
            
            ctx.arc(108,108,105, self.getDegree(0), self.getDegree((360/self.total)*(self.total - self.days)));
            ctx.lineWidth = 3;
            ctx.stroke();
            self.$container.find(".ss-clock-days-container .ss-value").text(self.days);
        },
        setHours: function(){
        	var  self = this;
        	
            var cHr = $(".ss-hours-canvas").get(0);
            var ctx = cHr.getContext("2d");
            ctx.clearRect(0, 0, cHr.width, cHr.height);
            ctx.beginPath();
            ctx.strokeStyle = self.options.hoursColor;
            
            ctx.shadowBlur    = 10;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
            
            ctx.arc(108,108,105, self.getDegree(0), self.getDegree(15*self.hours));
            ctx.lineWidth = 3;
            ctx.stroke();
            self.$container.find(".ss-clock-hours-container .ss-value").text(24 - self.hours);
        },
        setMinutes : function(){
        	var  self = this;
        	
            var cMin = $(".ss-minutes-canvas").get(0);
            var ctx = cMin.getContext("2d");
            ctx.clearRect(0, 0, cMin.width, cMin.height);
            ctx.beginPath();
            ctx.strokeStyle = self.options.minutesColor;
            
            ctx.shadowBlur    = 10;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
            
            ctx.arc(108,108,105, self.getDegree(0), self.getDegree(6*self.minutes));
            ctx.lineWidth = 3;
            ctx.stroke();
            self.$container.find(".ss-clock-minutes-container .ss-value").text(60 - self.minutes);
        },
        setSeconds: function(){
        	var  self = this;
        	
            var cSec = $(".ss-seconds-canvas").get(0);
            var ctx = cSec.getContext("2d");
            ctx.clearRect(0, 0, cSec.width, cSec.height);
            ctx.beginPath();
            ctx.strokeStyle = self.options.secondsColor;
            
            ctx.shadowBlur    = 10;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
            
            ctx.arc(108,108,105, self.getDegree(0), self.getDegree(6*self.seconds));
            ctx.lineWidth = 3;
            ctx.stroke();
    
            self.$container.find(".ss-clock-seconds-container .ss-value").text(60 - self.seconds);
        },
        getDegree: function(d) {
            return (Math.PI/180)*d - (Math.PI/180)*90;
        }
	};
	
	return (window.SSCountdown = SSCountdown);
})(jQuery_1_11_1, window);

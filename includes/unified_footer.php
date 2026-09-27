<?php
/**
 * UNIFIED FOOTER TEMPLATE
 * =====================================================
 * 
 * Closes the layout structure started in unified_header.php
 * Includes all necessary JavaScript for interactivity
 * 
 * USAGE:
 * ------
 * Include this at the BOTTOM of your page, after all content
 * Pairs with unified_header.php
 */
?>
                    <!-- End of content-wrapper -->
                </div>
                <!-- End of app-content -->
            </section>
            <!-- End of app-main -->
        </main>
        <!-- End of app-container -->
    </div>

    <!-- ============================================================
         SCRIPTS
         ============================================================ -->
    
    <!-- Bootstrap Bundle -->
    <script src="/bootstrap5/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main Application Scripts -->
    <script>
        'use strict';

        /**
         * ============================================================
         * LAYOUT MANAGEMENT
         * ============================================================
         */

        /**
         * Toggle Sidebar on Mobile
         */
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }

        /**
         * Close sidebar when a link is clicked (mobile)
         */
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar && window.innerWidth <= 768) {
                        sidebar.classList.remove('show');
                    }
                });
            });
        });

        /**
         * Close dropdowns when clicking outside
         */
        document.addEventListener('click', function(event) {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const notificationDropdown = document.querySelector('.notification-dropdown');
            
            if (profileDropdown && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.remove('active');
            }
            if (notificationDropdown && !notificationDropdown.contains(event.target)) {
                notificationDropdown.classList.remove('active');
            }
        });

        /**
         * ============================================================
         * PROFILE DROPDOWN
         * ============================================================
         */

        function toggleProfile(event) {
            event.stopPropagation();
            const dropdown = document.querySelector('.profile-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('active');
            }
        }

        /**
         * ============================================================
         * NOTIFICATIONS
         * ============================================================
         */

        let notificationInterval = null;

        /**
         * Toggle Notifications Dropdown
         */
        function toggleNotifications(event) {
            event.stopPropagation();
            const dropdown = document.querySelector('.notification-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('active');
            }
            loadNotifications();
        }

        /**
         * Load Notifications from API
         */
        async function loadNotifications() {
            try {
                const response = await fetch('/api/get_notifications.php?unread_only=0');
                const data = await response.json();
                
                if (data.success) {
                    updateNotificationUI(data.data, data.unread_count);
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                const list = document.getElementById('notificationList');
                if (list) {
                    list.innerHTML = '<div class="notification-empty">Error loading notifications</div>';
                }
            }
        }

        /**
         * Update Notification UI
         */
        function updateNotificationUI(notifications, unreadCount) {
            const badge = document.getElementById('notificationBadge');
            const list = document.getElementById('notificationList');
            
            if (!list) return;
            
            // Update badge
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
            
            // Update notification list
            if (notifications && notifications.length > 0) {
                list.innerHTML = notifications.map(notif => `
                    <div class="notification-item ${notif.is_read ? '' : 'unread'}" 
                         onclick="handleNotificationClick(${notif.id}, '${sanitizeAttr(notif.link || '')}')">
                        <div class="notification-icon ${notif.type}">
                            <i class="fas fa-${getNotificationIcon(notif.type)}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${escapeHtml(notif.title)}</div>
                            <div class="notification-message">${escapeHtml(notif.message)}</div>
                            <div class="notification-time">${notif.created_at_formatted || 'Just now'}</div>
                        </div>
                    </div>
                `).join('');
            } else {
                list.innerHTML = '<div class="notification-empty">No notifications</div>';
            }
        }

        /**
         * Get Icon for Notification Type
         */
        function getNotificationIcon(type) {
            const icons = {
                'info': 'info-circle',
                'success': 'check-circle',
                'warning': 'exclamation-triangle',
                'danger': 'times-circle',
                'assignment': 'file-alt',
                'announcement': 'bullhorn',
                'grade': 'chart-line'
            };
            return icons[type] || 'bell';
        }

        /**
         * Handle Notification Click
         */
        async function handleNotificationClick(id, link) {
            try {
                await fetch('/api/mark_notification_read.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ notification_id: id })
                });
                
                if (link && link !== '') {
                    window.location.href = link;
                } else {
                    loadNotifications();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        /**
         * Mark All Notifications as Read
         */
        async function markAllNotificationsRead() {
            try {
                const response = await fetch('/api/mark_notification_read.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mark_all: true })
                });
                
                if (response.ok) {
                    loadNotifications();
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }

        /**
         * ============================================================
         * UTILITY FUNCTIONS
         * ============================================================
         */

        /**
         * Escape HTML Special Characters
         */
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        /**
         * Sanitize Attribute Values
         */
        function sanitizeAttr(attr) {
            return attr.replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        /**
         * ============================================================
         * INITIALIZATION
         * ============================================================
         */

        document.addEventListener('DOMContentLoaded', function() {
            // Load initial notifications
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            notificationInterval = setInterval(loadNotifications, 30000);
            
            // Handle active navigation link
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar-menu a');
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPath || href === currentPath.replace(/\/$/, '') || href === currentPath.replace(/index\.php$/, '')) {
                    link.classList.add('active');
                }
            });
            
            // Handle window resize for mobile responsive
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    // Close sidebar on larger screens
                    if (window.innerWidth > 768) {
                        const sidebar = document.getElementById('sidebar');
                        if (sidebar) {
                            sidebar.classList.remove('show');
                        }
                    }
                }, 250);
            });
        });

        /**
         * Cleanup on page unload
         */
        window.addEventListener('beforeunload', function() {
            if (notificationInterval) {
                clearInterval(notificationInterval);
            }
        });
    </script>

</body>
</html>
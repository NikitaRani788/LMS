    </div>

    </main>

    <div class="footer">
        <div class="container-fluid">
            <p>&copy; 2026 Learning Management System (if0_41817906_lms). All Rights Reserved.</p>
                 <p style="font-size: 0.9rem; margin-top: 10px; opacity: 0.8;">Current Role: <strong><?php echo ucfirst($current_role ?? 'admin'); ?></strong></p>
   </div>
    </div>

    <!-- Scripts -->
    <script src="/bootstrap5/js/bootstrap.bundle.min.js"></script>
    <script>
        // Highlight active navigation link
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const links = document.querySelectorAll('.sidebar-menu a');
            links.forEach(link => {
                if (link.getAttribute('href') === currentPath || link.getAttribute('href') === currentPath.replace(/index\.php$/, '')) {
                    link.classList.add('active');
                }
            });
        });

        // ==================== NOTIFICATIONS ====================
        let notificationInterval = null;

        // Load notifications
        async function loadNotifications() {
            try {
                const response = await fetch('/api/get_notifications.php?unread_only=0');
                const data = await response.json();
                
                if (data.success) {
                    updateNotificationUI(data.data, data.unread_count);
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Update notification UI
        function updateNotificationUI(notifications, unreadCount) {
            const badge = document.getElementById('notificationBadge');
            const list = document.getElementById('notificationList');
            
            // Update badge
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
            
            // Update list
            if (notifications && notifications.length > 0) {
                list.innerHTML = notifications.map(notif => `
                    <div class="notification-item ${notif.is_read ? '' : 'unread'}" 
                         onclick="handleNotificationClick(${notif.id}, '${notif.link || ''}')">
                        <div class="notification-icon ${notif.type}">
                            <i class="fas fa-${getNotificationIcon(notif.type)}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${escapeHtml(notif.title)}</div>
                            <div class="notification-message">${escapeHtml(notif.message)}</div>
                            <div class="notification-time">${notif.created_at_formatted}</div>
                        </div>
                    </div>
                `).join('');
            } else {
                list.innerHTML = '<div class="notification-empty">No notifications</div>';
            }
        }

        // Get notification icon
        function getNotificationIcon(type) {
            const icons = {
                'info': 'info-circle',
                'success': 'check-circle',
                'warning': 'exclamation-triangle',
                'danger': 'times-circle'
            };
            return icons[type] || 'bell';
        }

        // Handle notification click
        async function handleNotificationClick(id, link) {
            try {
                await fetch('/api/mark_notification_read.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ notification_id: id })
                });
                
                loadNotifications();
                
                if (link) {
                    window.location.href = link;
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Mark all as read
        async function markAllRead(event) {
            event.stopPropagation();
            try {
                await fetch('/api/mark_notification_read.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mark_all: true })
                });
                
                loadNotifications();
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Initialize notifications
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            
            // Refresh every 30 seconds
            notificationInterval = setInterval(loadNotifications, 30000);
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (notificationInterval) {
                clearInterval(notificationInterval);
            }
        });
    </script>
</body>
</html>

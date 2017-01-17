package lib.Borhan.notification;

import lib.Borhan.config.SessionConfig;
import lib.Borhan.output.Console;

import com.borhan.client.BorhanApiException;
import com.borhan.client.BorhanClient;
import com.borhan.client.enums.BorhanSessionType;
import com.borhan.client.types.BorhanHttpNotification;

/**
 * This class is a base class for all the notification handlers 
 */
public abstract class BaseNotificationHandler {

	/** Borhan client */
	private static BorhanClient apiClient = null;
	
	/** The console this handler use*/
	protected Console console;
	
	/**
	 * Constructor
	 * @param console
	 */
	public BaseNotificationHandler(Console console) {
		this.console = console;
	}

	/**
	 * @return The Borhan client
	 * @throws Exception
	 */
	protected static BorhanClient getClient() {
		if (apiClient == null) {
			// Generates the Borhan client. The parameters can be changed according to the need
			try {
				apiClient = SessionConfig.getClient(BorhanSessionType.ADMIN, "", 86400, "");
			} catch (Exception e) {
				throw new NotificationHandlerException("Failed to generate client : " + e.getMessage(), NotificationHandlerException.ERROR_PROCESSING);
			}
		}
		return apiClient;
	}

	/**
	 * This function decides whether this handle should handle the notification
	 * @param httpNotification The notification that is considered to be handled
	 * @return Whether this handler should handle this notification
	 */
	abstract public boolean shouldHandle(BorhanHttpNotification httpNotification);

	/**
	 * The handling function. 
	 * @param httpNotification The notification that should be handled
	 * @throws BorhanApiException In case something bad happened
	 */
	abstract public void handle(BorhanHttpNotification httpNotification);

	/**
	 * @return The notification processing timing
	 */
	public HandlerProcessType getType() {
		return HandlerProcessType.PROCESS;
	}
}
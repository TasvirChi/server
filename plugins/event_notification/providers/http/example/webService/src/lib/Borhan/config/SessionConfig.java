package lib.Borhan.config;


import com.borhan.client.BorhanClient;
import com.borhan.client.BorhanConfiguration;
import com.borhan.client.enums.BorhanSessionType;

/**
 * This class centralizes the session configuration 
 */
public class SessionConfig {
	
	/** The partner who is executing this client */
	public static final int BORHAN_PARTNER_ID = PARTNER_ID;
	/** The secret of the indicated partner */
	public static final String BORHAN_ADMIN_SECRET = "BORHAN_ADMIN_SECRET";
	/** Borhan service url - the end point*/
	public static final String BORHAN_SERVICE_URL = "END-POINT";
	
	/**
	 * This function generates Borhan Client according to the given ids
	 * @param sessionType BorhanSessionType - whether the session is admin or user session
	 * @param userId String - The user ID.
	 * @param sessionExpiry int - The session expire value. 
	 * @param sessionPrivileges String - The session privileges. 
	 * @return The generated client
	 * @throws Exception In case the client generation failed for some reason.
	 */
	public static BorhanClient getClient(BorhanSessionType sessionType, String userId, int sessionExpiry, String sessionPrivileges) throws Exception {
		
		// Create BorhanClient object using the accound configuration
		BorhanConfiguration config = new BorhanConfiguration();
		config.setPartnerId(BORHAN_PARTNER_ID);
		config.setEndpoint(BORHAN_SERVICE_URL);
		BorhanClient client = new BorhanClient(config);
		
		// Generate KS string locally, without calling the API
		String ks = client.generateSession(
			BORHAN_ADMIN_SECRET,
			userId,
			sessionType,
			config.getPartnerId(),
			sessionExpiry,
			sessionPrivileges
		);
		client.setSessionId(ks);
		
		// Returns the BorhanClient object
		return client;
	}
}